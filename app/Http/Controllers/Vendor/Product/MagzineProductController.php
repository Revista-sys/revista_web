<?php

namespace App\Http\Controllers\Vendor\Product;

use GuzzleHttp\Client;
use App\Contracts\Repositories\AttributeRepositoryInterface;
use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Repositories\MagzineCategoryRepositoryInterface;
use App\Contracts\Repositories\ColorRepositoryInterface;
use App\Contracts\Repositories\DealOfTheDayRepositoryInterface;
use App\Contracts\Repositories\FlashDealProductRepositoryInterface;
use App\Contracts\Repositories\MagzineProductRepositoryInterface;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Contracts\Repositories\WishlistRepositoryInterface;
use App\Enums\ViewPaths\Vendor\MagzineProduct;
use App\Enums\WebConfigKey;
use App\Http\Controllers\BaseController;
use App\Http\Requests\MagzineProductAddRequest;
use App\Http\Requests\MagzineProductUpdateRequest;
use App\Repositories\TranslationRepository;
use App\Services\MagzineProductService;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;



class MagzineProductController extends BaseController
{
    protected $client;
    use FileManagerTrait {
        delete as deleteFile;
        update as updateFile;
    }


    public function __construct(
        private readonly MagzineCategoryRepositoryInterface         $categoryRepo,
        private readonly BrandRepositoryInterface            $brandRepo,
        private readonly MagzineProductRepositoryInterface          $productRepo,
        private readonly TranslationRepository               $translationRepo,
        private readonly BusinessSettingRepositoryInterface  $businessSettingRepo,
        private readonly ColorRepositoryInterface            $colorRepo,
        private readonly AttributeRepositoryInterface        $attributeRepo,
        private readonly ReviewRepositoryInterface           $reviewRepo,
        private readonly CartRepositoryInterface             $cartRepo,
        private readonly WishlistRepositoryInterface         $wishlistRepo,
        private readonly FlashDealProductRepositoryInterface $flashDealProductRepo,
        private readonly DealOfTheDayRepositoryInterface     $dealOfTheDayRepo,
    )
    {
         $this->client = new Client();
    }


    /**
     * @param Request|null $request
     * @param string|array|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     * Index function is the starting point of a controller
     */
    public function index(?Request $request, string|array $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getListView(request: $request,type: $type);
    }

    public function getListView(Request $request,$type): View
    {
        $vendorId = auth('seller')->id();
        $filters = [
            'added_by' => 'seller',
            'seller_id' => $vendorId,
            'brand_id' => $request['brand_id'],
            'category_id' => $request['category_id'],
            'sub_category_id' => $request['sub_category_id'],
            'sub_sub_category_id' => $request['sub_sub_category_id'],
            'request_status' => $type== 'new-request' ? 0 : ($type == 'approved'  ? '1' : ($type == 'denied' ? '2' : 'all')),
        ];
        $searchValue = $request['searchValue'];
        $products = $this->productRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            searchValue: $searchValue,
            filters: $filters,
            relations: ['translations'],
            dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT)
        );
        $brands = $this->brandRepo->getListWhere(filters: ['status' => 1], dataLimit: 'all');
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $subCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_category_id']]);
        $subSubCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_sub_category_id']]);

        return view(MagzineProduct::LIST[VIEW], compact('products', 'type','searchValue', 'brands',
            'categories', 'subCategory', 'subSubCategory', 'filters'));
    }

    public function getAddView(): View
    {
        $languages = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'pnc_language']);
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $brands = $this->brandRepo->getListWhere(filters: ['status' => 1], dataLimit: 'all');
        $brandSetting = getWebConfig(name: 'product_brand');
        $digitalProductSetting = getWebConfig(name: 'digital_product');
        $colors = $this->colorRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $attributes = $this->attributeRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $languages = getWebConfig(name: 'pnc_language') ?? null;
        $defaultLanguage = $languages[0];

        return view(MagzineProduct::ADD[VIEW], compact('languages', 'categories', 'brands', 'brandSetting', 'digitalProductSetting', 'colors', 'attributes', 'languages', 'defaultLanguage'));
    }

    public function add(MagzineProductAddRequest $request, MagzineProductService $service): JsonResponse|RedirectResponse
    {
        if ($request->ajax()) {
            return response()->json([], 200);
        }

        $dataArray = $service->getAddProductData(request: $request, addedBy: 'seller');
        $savedProduct = $this->productRepo->add(data: $dataArray);
        $this->productRepo->addRelatedTags(request: $request, product: $savedProduct);
        $this->translationRepo->add(request: $request, model: 'App\Models\MagzineProduct', id: $savedProduct->id);

        Toastr::success(translate('product_added_successfully'));
        return redirect()->route('vendor.magzineproducts.list',['type'=>'all']);
    }

    public function getUpdateView(string|int $id): RedirectResponse|View
    {
        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $id, 'user_id'=>auth('seller')->id(), 'added_by'=>'seller'], relations: ['translations']);
        if(!$product){
            Toastr::error(translate('invalid_product'));
            return redirect()->route('vendor.magzineproducts.list',['type'=>'all']);
        }

        $product['colors'] = json_decode($product['colors']);
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $brands = $this->brandRepo->getListWhere(filters: ['status' => 1], dataLimit: 'all');
        $brandSetting = getWebConfig(name: 'product_brand');
        $digitalProductSetting = getWebConfig(name: 'digital_product');
        $colors = $this->colorRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $attributes = $this->attributeRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $languages = getWebConfig(name: 'pnc_language') ?? null;
        $defaultLanguage = $languages[0];

        return view(MagzineProduct::UPDATE[VIEW], compact('product', 'categories', 'brands', 'brandSetting', 'digitalProductSetting', 'colors', 'attributes', 'languages', 'defaultLanguage'));
    }

    public function update(MagzineProductUpdateRequest $request, MagzineProductService $service, string|int $id): JsonResponse|RedirectResponse
    {
        if ($request->ajax()) {
            return response()->json([], 200);
        }

        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $id], relations: ['translations']);
        $dataArray = $service->getUpdateProductData(request: $request, product: $product, updateBy: 'seller');

        $this->productRepo->update(id: $id, data: $dataArray);
        $this->productRepo->addRelatedTags(request: $request, product: $product);
        $this->translationRepo->update(request: $request, model: 'App\Models\MagzineProduct', id: $id);

        Toastr::success(translate('product_updated_successfully'));
        return redirect()->route(MagzineProduct::VIEW[ROUTE],['id'=>$product['id']]);

    }



   public function subscribe(Request $request)
{
    $amount = $request->input('price');
    $description = 'magzine payment'; // Payment description
    $currency = 'KWD'; // Currency code

    // Retrieve authenticated vendor's details
    $vendor = auth('seller')->user();

    // dd($vendor);

    if (!$vendor) {
        return back()->with('error', 'Unauthorized access.');
    }

    $checkoutname = $vendor['f_name'];
    $checkoutemail = $vendor['email'];
    //$checkoutphone = $vendor['phone'];
    $checkoutphone = '9661234567';

//         $response = $this->client->post('https://apitest.myfatoorah.com/v2/SendPayment', [
//             'json' => [
//                 'InvoiceValue' => $amount,
//                 'DisplayCurrencyIso' => $currency,
//                 'CustomerName' => $checkoutname,
//                 'CustomerEmail' => $checkoutemail,
//                 'CustomerMobile' => $checkoutphone,
//                 'Description' => $description,
//                 'CallbackUrl' => "https://revista.lmscloud.in/magzine-verify-payment",
//                 'ErrorUrl' => "https://revista.lmscloud.in/magzine-payment-error",
//                 'Language' => 'en',
//                 'NotificationOption' => 'ALL'
//             ],
//             'headers' => [
//                 'Authorization' => 'Bearer rWYRPLYiXuOsqeD7twqNgJ4YYPt4UVo6dBUcqd7GmB6aE-knpoBMTM3YhZHNk3-rHpRqUoXt9svY5UcD7cvQmW1ItWh5E58izrR3B0bl_WdoJpTIeuDBG1oyXFQxTTmHnEXhHA0bIQ5mM7mpoXoN-VOShI63e5nlZzLmGMPxJDDT8aUXr0_EU6Tgb1VuKT_jT1lKe9F_HQr1iF-O9oMyDskRKMZQHsa_e68FvAofyiZ3Vdb4XpJRWNxofisMdPBrYsVkadvKF2MAwGo3B_sncJiv1gqCs4Y6hfFL0-x3rGCR9OJ394D2ri3FakLYEpwgYPu0VdkIuhLOjyBxG3CqUMd3jDV1MFOBYXLlls1kS4NmJQFJ6nCK33RypGOsTppcko_RCX5Wjb6tLFQk8cZB5Zm7oP4JO0pLTmrIxg1cPbY6kOgIR7IfGqkQOYSEEkPR4hAYyLF06pknLmbIYTpOJU-xwzXh_gFSYOEcvi5VsdoBc6Iyl_KHRNSMzTswjFQ53Nbn1buT9TvJ2BIxE7dPK-pKA63YstmIPJmJ6nEEWCX4UIeaLxREUy5XPJYbDyWsmD7YnGJSwX0s1TWKrQOc2xVZTsLu_zOzMs7tInHwJpygkk_KyuWYTF3-nI9h2Cvg7DZcLMMh7U3D40HvmnBalw5IkeOwjt7EzWzRmx3N1n9GAv6e'
//             ]
//         ]);

//         $responseBody = json_decode($response->getBody()->getContents());

//        if (isset($responseBody->IsSuccess) && !$responseBody->IsSuccess) {
//             $errorMessage = $responseBody->Message ?? 'Unknown error';
//             if (isset($responseBody->ValidationErrors)) {
//                 foreach ($responseBody->ValidationErrors as $error) {
//                     $errorMessage .= "\n" . $error->Name . ": " . $error->Error;
//                 }
//             }
//             return ['error' => $errorMessage];
//         }



// $productid = $request->input('productid');
// $dataArray = [
//             'transaction_ref' => $responseBody->Data->InvoiceId,
//             'payment_info' => json_encode($responseBody->Data)
//             ];
//  $this->productRepo->update(id: $productid, data: $dataArray);
// return response()->json(['invoice_url' => $responseBody->Data->InvoiceURL]);



try {
        // Send payment request to MyFatoorah API
        $response = $this->client->post('https://apitest.myfatoorah.com/v2/SendPayment', [
            'json' => [
                'InvoiceValue' => $amount,
                'DisplayCurrencyIso' => $currency,
                'CustomerName' => $checkoutname,
                'CustomerEmail' => $checkoutemail,
                'CustomerMobile' => $checkoutphone,
                'Description' => $description,
                'CallbackUrl' => "https://revista.lmscloud.in/magzine-verify-payment",
                'ErrorUrl' => "https://revista.lmscloud.in/magzine-payment-error",
                'Language' => 'en',
                'NotificationOption' => 'ALL'
            ],
            'headers' => [
                  'Authorization' => 'Bearer rWYRPLYiXuOsqeD7twqNgJ4YYPt4UVo6dBUcqd7GmB6aE-knpoBMTM3YhZHNk3-rHpRqUoXt9svY5UcD7cvQmW1ItWh5E58izrR3B0bl_WdoJpTIeuDBG1oyXFQxTTmHnEXhHA0bIQ5mM7mpoXoN-VOShI63e5nlZzLmGMPxJDDT8aUXr0_EU6Tgb1VuKT_jT1lKe9F_HQr1iF-O9oMyDskRKMZQHsa_e68FvAofyiZ3Vdb4XpJRWNxofisMdPBrYsVkadvKF2MAwGo3B_sncJiv1gqCs4Y6hfFL0-x3rGCR9OJ394D2ri3FakLYEpwgYPu0VdkIuhLOjyBxG3CqUMd3jDV1MFOBYXLlls1kS4NmJQFJ6nCK33RypGOsTppcko_RCX5Wjb6tLFQk8cZB5Zm7oP4JO0pLTmrIxg1cPbY6kOgIR7IfGqkQOYSEEkPR4hAYyLF06pknLmbIYTpOJU-xwzXh_gFSYOEcvi5VsdoBc6Iyl_KHRNSMzTswjFQ53Nbn1buT9TvJ2BIxE7dPK-pKA63YstmIPJmJ6nEEWCX4UIeaLxREUy5XPJYbDyWsmD7YnGJSwX0s1TWKrQOc2xVZTsLu_zOzMs7tInHwJpygkk_KyuWYTF3-nI9h2Cvg7DZcLMMh7U3D40HvmnBalw5IkeOwjt7EzWzRmx3N1n9GAv6e' // Use a secure way to manage your API key
            ]
        ]);

        $responseBody = json_decode($response->getBody()->getContents());

        //dd($responseBody);
        // Handle API response
        if (isset($responseBody->IsSuccess) && !$responseBody->IsSuccess) {
            $errorMessage = $responseBody->Message ?? 'Unknown error';
            if (isset($responseBody->ValidationErrors)) {
                foreach ($responseBody->ValidationErrors as $error) {
                    $errorMessage .= "\n" . $error->Name . ": " . $error->Error;
                }
            }
            return response()->json(['error' => $errorMessage]);
        }
        // Extract product ID and update product info
        $productid = $request->input('productid');
        $dataArray = [
            'transaction_ref' => $responseBody->Data->InvoiceId,
            'payment_info' => json_encode($responseBody->Data)
        ];
        $this->productRepo->update(id: $productid, data: $dataArray);
        // Return invoice URL
        return response()->json(['invoice_url' => $responseBody->Data->InvoiceURL]);

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        // Handle client exceptions
        $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
        $errorMessage = $responseBody['Message'] ?? 'Client exception occurred';
        $details = $responseBody['ValidationErrors'] ?? [];
        return response()->json([
            'error' => $errorMessage,
            'details' => $details
        ]);
    } catch (\Exception $e) {
        // Handle general exceptions
        return response()->json([
            'error' => 'An unexpected error occurred: ' . $e->getMessage()
        ]);
    }



}


    public function getView(string|int $id): View|RedirectResponse
    {
        $vendorId =  auth('seller')->id();
        $productActive = $this->productRepo->getFirstWhereActive(params: ['id' => $id, 'user_id' =>$vendorId]);
        $relations = ['category', 'brand', 'reviews', 'rating', 'orderDetails', 'orderDelivered','translations'];
        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $id,'user_id' => $vendorId], relations: $relations);
        if(!$product){
            return redirect()->route('vendor.magzineproducts.list',['type'=>'all']);
        }
        $product['orderDelivered']->map(function ($order) use ($product) {
            $product['priceSum'] += $order->price;
            $product['qtySum'] += $order->qty;
            $product['discountSum'] += $order->discount;
        });

        $productColors = [];
        $colors = json_decode($product['colors']);
        foreach ($colors as $color) {
            $getColor = $this->colorRepo->getFirstWhere(params: ['code' => $color]);
            if ($getColor) {
                $productColors[$getColor['name']] = $colors;
            }
        }

        $reviews = $this->reviewRepo->getListWhere(filters: ['product_id' => ['product_id' => $id], 'whereNull' => ['column' => 'delivery_man_id']], dataLimit: getWebConfig(name: 'pagination_limit'));
        return view(MagzineProduct::VIEW[VIEW], compact('product', 'reviews', 'productActive', 'productColors'));
    }

    public function exportList(Request $request,$type): StreamedResponse|string
    {
        $filters = [
            'added_by' => 'seller',
            'seller_id' => auth('seller')->id(),
            'brand_id' => $request['brand_id'],
            'category_id' => $request['category_id'],
            'sub_category_id' => $request['sub_category_id'],
            'sub_sub_category_id' => $request['sub_sub_category_id'],
            'request_status' => $type== 'new-request' ? 0 : ($type == 'approved'  ? 1 : 'all'),
        ];
        $products = $this->productRepo->getListWhere(orderBy: ['id' => 'desc'], searchValue: $request['searchValue'], filters: $filters, relations: ['translations'], dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));

        //export from product
        $storage = [];
        foreach ($products as $item) {
            $category_id = 0;
            $sub_category_id = 0;
            $sub_sub_category_id = 0;
            foreach (json_decode($item->category_ids, true) as $category) {
                if ($category['position'] == 1) {
                    $category_id = $category['id'];
                } else if ($category['position'] == 2) {
                    $sub_category_id = $category['id'];
                } else if ($category['position'] == 3) {
                    $sub_sub_category_id = $category['id'];
                }
            }
            $storage[] = [
                'name' => $item->name,
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id,
                'sub_sub_category_id' => $sub_sub_category_id,
                'brand_id' => $item->brand_id,
                'unit' => $item->unit,
                'minimum_order_qty' => $item->minimum_order_qty,
                'refundable' => $item->refundable,
                'youtube_video_url' => $item->video_url,
                'unit_price' => $item->unit_price,
                'tax' => $item->tax,
                'discount' => $item->discount,
                'discount_type' => $item->discount_type,
                'current_stock' => $item->current_stock,
                'details' => $item->details,
                'thumbnail' => 'thumbnail/' . $item->thumbnail

            ];
        }
        return (new FastExcel($storage))->download('products.xlsx');
    }

    public function getSkuCombinationView(Request $request, MagzineProductService $service): JsonResponse
    {
        $combinationView = $service->getSkuCombinationView(request: $request);
        return response()->json(['view' => $combinationView]);
    }

    public function getCategories(Request $request, MagzineProductService $service): JsonResponse
    {
        $parentId = $request['parent_id'];
        $filter = ['parent_id' => $parentId];
        $categories = $this->categoryRepo->getListWhere(filters: $filter, dataLimit: 'all');
        $dropdown = $service->getCategoryDropdown(request: $request, categories: $categories);

        $childCategories = '';
        if (count($categories) == 1) {
            $subCategories = $this->categoryRepo->getListWhere(filters: ['parent_id' => $categories[0]['id']], dataLimit: 'all');
            $childCategories = $service->getCategoryDropdown(request: $request, categories: $subCategories);
        }

        return response()->json([
            'select_tag' => $dropdown,
            'sub_categories' => count($categories) == 1 ? $childCategories : '',
        ]);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $status = $request['status'];
        $productId = $request['id'];
        $product = $this->productRepo->getFirstWhere(params: ['id' => $productId, 'user_id' => auth('seller')->id()]);
        $success = 0;

        if ($status == 1 && $product['request_status'] == 1) {
            $this->productRepo->update(id: $productId, data: ['status' => $status]);
            $success = 1;
        } elseif ($status != 1) {
            $this->productRepo->update(id: $productId, data: ['status' => $status ?? 0]);
            $success = 1;
        }

        return response()->json([
            'success' => $success,
            'message' => $success ? translate("status_updated_successfully") : translate("status_updated_failed").' '.translate("Product_must_be_approved"),
        ], 200);
    }

    public function getBarcodeView(Request $request, string|int $id): View|RedirectResponse
    {
        if ($request['limit'] > 270) {
            Toastr::warning(translate('you_can_not_generate_more_than_270_barcode'));
            return back();
        }
        $product = $this->productRepo->getFirstWhere(params: ['id' => $id, 'user_id' => auth('seller')->id()]);
        $rangeData = range(1, $request->limit ?? 4);
        $barcodes = array_chunk($rangeData, 24);
        return view(MagzineProduct::BARCODE_VIEW[VIEW], compact('product', 'barcodes'));
    }

    public function delete(string|int $id, MagzineProductService $service): RedirectResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id' => $id, 'user_id' => auth('seller')->id()]);

        if($product){
            $this->translationRepo->delete(model: 'App\Models\MagzineProduct', id: $id);
            $this->cartRepo->delete(params: ['product_id' => $id]);
            $this->wishlistRepo->delete(params: ['product_id' => $id]);
            $this->flashDealProductRepo->delete(params: ['product_id' => $id]);
            $this->dealOfTheDayRepo->delete(params: ['product_id' => $id]);
            $service->deleteImages(product: $product);
            $this->productRepo->delete(params: ['id' => $id]);
            Toastr::success(translate('product_removed_successfully'));
        }else{
            Toastr::error(translate('invalid_product'));
        }

        return back();
    }

    public function getStockLimitListView(Request $request): View
    {
        $vendorId = auth('seller')->id();
        $stockLimit = getWebConfig(name: 'stock_limit');
        $sortOrderQty = $request['sortOrderQty'];
        $searchValue = $request['searchValue'];
        $withCount = ['orderDetails'];
        $status = $request['status'];
        $filters = [
            'added_by' => 'seller',
            'request_status' => 1,
            'product_type' => 'physical',
            'seller_id' => $vendorId,
        ];

        $orderBy = [];
        if ($sortOrderQty == 'quantity_asc') {
            $orderBy = ['current_stock' => 'asc'];
        }else if ($sortOrderQty == 'quantity_desc') {
            $orderBy = ['current_stock' => 'desc'];
        } elseif ($sortOrderQty == 'order_asc') {
            $orderBy = ['order_details_count' => 'asc'];
        } elseif ($sortOrderQty == 'order_desc') {
            $orderBy = ['order_details_count' => 'desc'];
        } elseif ($sortOrderQty == 'default') {
            $orderBy = ['id' => 'asc'];
        }

        $products = $this->productRepo->getStockLimitListWhere(orderBy: $orderBy, searchValue: $searchValue, filters: $filters, withCount: $withCount, relations: ['translations'], dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        return view(MagzineProduct::STOCK_LIMIT[VIEW], compact('products', 'searchValue', 'status', 'sortOrderQty', 'stockLimit'));
    }

    public function updateQuantity(Request $request): RedirectResponse
    {
        $variations = [];
        $stockCount = $request['current_stock'];
        if ($request->has('type')) {
            foreach ($request['type'] as $key => $str) {
                $item = [];
                $item['type'] = $str;
                $item['price'] = currencyConverter(amount: abs($request['price_' . str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_' . str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_' . str_replace('.', '_', $str)]);
                $variations[] = $item;
            }
        }
        $dataArray = [
            'current_stock' => $stockCount,
            'variation' => json_encode($variations),
        ];

        if ($stockCount >= 0) {
            $this->productRepo->update(id: $request['product_id'], data: $dataArray);
            Toastr::success(translate('product_quantity_updated_successfully'));
            return back();
        }
        Toastr::warning(translate('product_quantity_can_not_be_less_than_0_'));
        return back();
    }

    public function deleteImage(Request $request, MagzineProductService $service): RedirectResponse
    {
        $this->deleteFile(filePath: '/product/' . $request['image']);
        $product = $this->productRepo->getFirstWhere(params: ['id' => $request['id']]);

        if (count(json_decode($product['images'])) < 2) {
            Toastr::warning(translate('you_can_not_delete_all_images'));
            return back();
        }
        $imageProcessing = $service->deleteImage(request: $request, product: $product);
        $updateData = [
            'images' => json_encode($imageProcessing['images']),
            'color_image' => json_encode($imageProcessing['color_images']),
        ];
        $this->productRepo->update(id: $request['id'], data: $updateData);

        Toastr::success(translate('product_image_removed_successfully'));
        return back();
    }

    public function getVariations(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id' => $request['id']]);
        return response()->json([
            'view' => view(MagzineProduct::GET_VARIATIONS[VIEW], compact('product'))->render()
        ]);
    }

    public function getBulkImportView(): View
    {
        return view(MagzineProduct::BULK_IMPORT[VIEW]);
    }

    public function importBulkProduct(Request $request, MagzineProductService $service): RedirectResponse
    {
        $dataArray = $service->getImportBulkProductData(request: $request, addedBy: 'seller');
        if (!$dataArray['status']) {
            Toastr::error($dataArray['message']);
            return back();
        }

        $this->productRepo->addArray(data: $dataArray['products']);
        Toastr::success($dataArray['message']);
        return back();
    }

    public function getSearchedProductsView(Request $request):JsonResponse
    {
        $searchValue = $request['searchValue'] ?? null;
        $products = $this->productRepo->getListWhere(
            searchValue:$searchValue,
            filters: [
                'added_by' => 'seller',
                'seller_id' => auth('seller')->id(),
                'status' => 1,
                'category_id' => $request['category_id'],
                'code' => $request['name']??null,
            ],
            dataLimit:FILTER_PRODUCT_DATA_LIMIT
        );
        return response()->json([
            'count' => $products->count(),
            'result' => view(MagzineProduct::SEARCH[VIEW], compact('products'))->render(),
        ]);
    }

}
