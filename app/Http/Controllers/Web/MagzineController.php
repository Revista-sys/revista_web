<?php

namespace App\Http\Controllers\Web;

use App\Models\Admin;
use App\Utils\Helpers;
use App\Events\DigitalProductOtpVerificationMailEvent;
use App\Http\Controllers\Controller;
use App\Models\OfflinePaymentMethod;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\ShippingType;
use App\Models\Shop;
use App\Models\Subscription;
use App\Models\OrderDetail;
use App\Models\Review;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Cart;
use App\Models\CartShipping;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\DeliveryZipCode;
use App\Models\DigitalProductOtpVerification;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCompare;
use App\Models\Seller;
use App\Models\Setting;
use App\Models\Wishlist;
use App\Traits\CommonTrait;
use App\Traits\SmsGateway;
use App\Utils\CartManager;
use App\Utils\Convert;
use App\Utils\CustomerManager;
use App\Utils\OrderManager;
use App\Utils\ProductManager;
use App\Utils\SMS_module;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use function App\Utils\payment_gateways;

class MagzineController extends Controller
{
    use CommonTrait;
    use SmsGateway;

    public function __construct(
        private OrderDetail $order_details,
        private Product $product,
        private Wishlist $wishlist,
        private Order $order,
        private Category $category,
        private Brand $brand,
        private Seller $seller,
        private ProductCompare $compare,
    ) {

    }

 public function index(Request $request)
    {
       $businessMode = getWebConfig(name: 'business_mode');
        if (isset($businessMode) && $businessMode == 'single') {
            Toastr::warning(translate('access_denied') . ' !!');
            return back();
        }
        $sellers = Shop::active()->with(['seller.product'])
            ->withCount(['products' => function ($query) {
                $query->active();
            }])
            ->when(isset($request['shop_name']), function ($query) use ($request) {
                $key = explode(' ', $request['shop_name']);
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })->get();



        if (theme_root_path() == 'theme_fashion') {
//
            if ($request->has('order_by') && ($request->order_by == 'rating-high-to-low' || $request->order_by == 'rating-low-to-high')) {
                if ($request->order_by == 'rating-high-to-low') {
                    $sellers = $sellers->sortByDesc('average_rating');
                } else {
                    $sellers = $sellers->sortBy('rating_count');
                }
            }
        }

        $inhouseProducts = Product::active()->with(['reviews', 'rating'])->withCount('reviews')->where(['added_by' => 'admin'])->get();
        $inhouseProductCount = $inhouseProducts->count();

        $inhouseVacation = getWebConfig(name: 'vacation_add');
        $inhouseShop = new Shop([
            'id' => 0,
            'seller_id' => 0,
            'name' => getWebConfig(name: 'company_name'),
            'slug' => Str::slug(getWebConfig(name: 'company_name')),
            'address' => getWebConfig(name: 'shop_address'),
            'contact' => getWebConfig(name: 'company_phone'),
            'image' => getWebConfig(name: 'company_fav_icon'),
            'bottom_banner' => getWebConfig(name: 'bottom_banner'),
            'offer_banner' => getWebConfig(name: 'offer_banner'),
            'vacation_start_date' => $inhouseVacation['vacation_start_date'] ?? null,
            'vacation_end_date' => $inhouseVacation['vacation_end_date'] ?? null,
            'vacation_note' => $inhouseVacation['vacation_note'],
            'vacation_status' => $inhouseVacation['status'] ?? false,
            'temporary_close' => getWebConfig(name: 'temporary_close') ? getWebConfig(name: 'temporary_close')['status'] : 0,
            'banner' => getWebConfig(name: 'shop_banner'),
            'created_at' => Admin::where(['id' => 1])->first()->created_at,
        ]);

        if (!(isset($request['shop_name']) && !str_contains(strtolower(getWebConfig(name: 'company_name')), strtolower($request['shop_name'])))) {
            $sellers = $sellers->prepend($inhouseShop);
        }

        $sellers?->map(function ($seller) use ($inhouseProducts, $inhouseProductCount) {
            if ($seller['id'] != 0) {
                $productIds = Product::active()->where(['added_by' => 'seller'])
                    ->where('user_id', $seller['id'])->pluck('id')->toArray();
                $vendorReviewData = Review::active()->whereIn('product_id', $productIds);
                $seller['average_rating'] = $vendorReviewData->avg('rating');
                $seller['review_count'] = $vendorReviewData->count();
                $seller['total_rating'] = $vendorReviewData->count();

                $vendorRattingStatusPositive = 0;
                foreach($vendorReviewData->pluck('rating') as $singleRating) {
                    ($singleRating >= 4?($vendorRattingStatusPositive++):'');
                }

                $seller['positive_review'] = $seller['review_count'] != 0 ? ($vendorRattingStatusPositive*100)/ $seller['review_count']:0;
            } else {
                $inhouseReviewData = Review::active()->whereIn('product_id', $inhouseProducts->pluck('id'));
                $inhouseRattingStatusPositive = 0;
                foreach($inhouseReviewData->pluck('rating') as $singleRating) {
                    ($singleRating >= 4?($inhouseRattingStatusPositive++):'');
                }

                $seller['id'] = 0;
                $seller['products_count'] = $inhouseProductCount;
                $seller['total_rating'] = $inhouseReviewData->count();
                $seller['review_count'] = $inhouseReviewData->count();
                $seller['average_rating'] = $inhouseReviewData->avg('rating');
                $seller['positive_review'] = $inhouseReviewData->count() != 0 ? ($inhouseRattingStatusPositive*100)/ $inhouseReviewData->count():0;
            }
        });

        if ($request->has('order_by')) {
            if ($request['order_by'] == 'asc') {
                $sellers = $sellers->sortBy('name');
            } else if ($request['order_by'] == 'desc') {
                $sellers = $sellers->sortByDesc('name');
            } else if ($request['order_by'] == 'highest-products') {
                $sellers = $sellers->sortByDesc('products_count');
            } else if ($request['order_by'] == 'lowest-products') {
                $sellers = $sellers->sortBy('products_count');
            } else if ($request['order_by'] == 'rating-high-to-low') {
                $sellers = $sellers->sortByDesc('average_rating');
            } else if ($request['order_by'] == 'rating-low-to-high') {
                $sellers = $sellers->sortBy('average_rating');
            };
        }

        return view(VIEW_FILE_NAMES['all_magzine_page'], [
            'sellers' => $sellers->paginate(12),
            'order_by' => $request['order_by'],
        ]);

    }




}
