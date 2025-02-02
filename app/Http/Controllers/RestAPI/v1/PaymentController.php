<?php

namespace App\Http\Controllers\RestAPI\v1;

// use App\Http\Controllers\Controller;
 // use App\Models\MagzineCategory;
use App\Models\MostDemanded;
 use App\Models\Order;
// use App\Models\OrderDetail;
// use App\Models\MagzineProduct;
// use App\Models\Review;
// use App\Models\ShippingMethod;
// use App\Models\Wishlist;
// use App\Utils\CategoryManager;
// use App\Utils\Helpers;
// use App\Utils\ImageManager;
// use App\Utils\ProductManager;
// use Illuminate\Http\JsonResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;



use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\MagzineCategory;
use App\Models\Color;
use App\Models\DealOfTheDay;
use App\Models\DeliveryMan;
use App\Models\FlashDealProduct;
use App\Models\OrderDetail;
use App\Models\MagzineProduct;
use App\Models\Review;
use App\Models\Tag;
use App\Models\Translation;
use App\Utils\Convert;
use App\Utils\Helpers;
use App\Utils\ImageManager;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class PaymentController extends Controller
{
    public function __construct(
        private MagzineProduct      $product,
        private Order        $order,
        private MostDemanded $most_demanded,
    ){}


public function verifyPayment(Request $request)
{


echo '<pre>';
print_r($request->all());
echo '</pre>';

//     $paymentId = $request->input('PaymentId');
//     $status = $request->input('PaymentStatus');

//     // Update the order status based on payment status
//     $order = Order::where('transaction_ref', $paymentId)->first();
//     if ($order) {
//         $order->payment_status = $status == 'Succeeded' ? 'paid' : 'failed';
//         $order->save();
//     }


// return "payment faield";

    //return redirect()->route('order-complete'); // Redirect to a completion page




}


public function paymentError(Request $request)
{
    // Handle payment error
    // return redirect()->route('order-failed'); // Redirect to a failure page
echo '<pre>';
print_r($request->all());
echo '</pre>';

//return "payment faield";

}


}
