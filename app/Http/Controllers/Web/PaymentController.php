<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Services\MyFatoorahService;
use Illuminate\Routing\Controller;

use GuzzleHttp\Client;
use App\Models\Admin;

use App\Utils\Helpers;
use App\Events\DigitalProductOtpVerificationMailEvent;
use App\Models\OfflinePaymentMethod;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\ShippingType;
use App\Models\Shop;
use App\Models\User;
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
use App\Models\PrimeSubscription;
use App\Models\MagzineProduct;
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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use function App\Utils\payment_gateways;

class PaymentController extends Controller
{

  use CommonTrait;
    use SmsGateway;

 protected $client;
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

   $this->client = new Client();
    }

    public function createPayment(Request $request)
    {
        $service = new MyFatoorahService();
        $response = $service->createPayment(
            $request->amount,
            $request->currency,
            $request->invoiceId,
            $request->description
        );

        return response()->json($response);
    }

    // public function verifyPayment(Request $request)
    // {
    //     $service = new MyFatoorahService();
    //     $response = $service->verifyPayment($request->paymentId);

    //     // Handle the verification result
    //     return response()->json($response);
    // }


public function verifyPayment(Request $request)
{

// echo "<pre>";

// print_r($request->all());


    $paymentId = $request->input('paymentId');
    //$status = $request->input('PaymentStatus');





try {
    $paymentId = $request->input('paymentId');

    //echo $paymentId;
    if($paymentId)
    {
    $response = $this->client->post("https://apitest.myfatoorah.com/v2/GetPaymentStatus", [
        'json' => [
            'Key' => $paymentId,
            'KeyType' => 'PaymentId'
        ],
        'headers' => [
            'Authorization' => 'Bearer rWYRPLYiXuOsqeD7twqNgJ4YYPt4UVo6dBUcqd7GmB6aE-knpoBMTM3YhZHNk3-rHpRqUoXt9svY5UcD7cvQmW1ItWh5E58izrR3B0bl_WdoJpTIeuDBG1oyXFQxTTmHnEXhHA0bIQ5mM7mpoXoN-VOShI63e5nlZzLmGMPxJDDT8aUXr0_EU6Tgb1VuKT_jT1lKe9F_HQr1iF-O9oMyDskRKMZQHsa_e68FvAofyiZ3Vdb4XpJRWNxofisMdPBrYsVkadvKF2MAwGo3B_sncJiv1gqCs4Y6hfFL0-x3rGCR9OJ394D2ri3FakLYEpwgYPu0VdkIuhLOjyBxG3CqUMd3jDV1MFOBYXLlls1kS4NmJQFJ6nCK33RypGOsTppcko_RCX5Wjb6tLFQk8cZB5Zm7oP4JO0pLTmrIxg1cPbY6kOgIR7IfGqkQOYSEEkPR4hAYyLF06pknLmbIYTpOJU-xwzXh_gFSYOEcvi5VsdoBc6Iyl_KHRNSMzTswjFQ53Nbn1buT9TvJ2BIxE7dPK-pKA63YstmIPJmJ6nEEWCX4UIeaLxREUy5XPJYbDyWsmD7YnGJSwX0s1TWKrQOc2xVZTsLu_zOzMs7tInHwJpygkk_KyuWYTF3-nI9h2Cvg7DZcLMMh7U3D40HvmnBalw5IkeOwjt7EzWzRmx3N1n9GAv6e',  // Replace with your actual token
            'Accept'        => 'application/json',
        ],
    ]);

    // Capture and decode the response body
    $responseBody = $response->getBody()->getContents();
    $responseData = json_decode($responseBody, true);

// echo "<pre>";
// print_r($responseData);die;

$status= null;

    // If you need to access TransactionStatus for the first transaction
if (!empty($responseData['Data']['InvoiceTransactions'])) {
    $status = $responseData['Data']['InvoiceTransactions'][0]['TransactionStatus'];
}


$invoiceid= null;

    // If you need to access TransactionStatus for the first transaction
if (!empty($responseData['Data']['InvoiceId'])) {
    $invoiceid = $responseData['Data']['InvoiceId'];
}



    $order = Order::where('transaction_ref', $invoiceid)->first();
    if ($order) {
        $order->payment_status = $status == 'Succss' ? 'paid' : 'failed';
        $order->save();
    }

}

} catch (\GuzzleHttp\Exception\RequestException $e) {
    // Handle and display the exception
    echo "<pre>";
    echo "Request failed: " . $e->getMessage();
    echo "</pre>";
}




    // Update the order status based on payment status

//}
$order_ids=[];
array_push($order_ids, $order->id);
return view(VIEW_FILE_NAMES['order_complete'], compact('order_ids'));
   // return redirect()->route('order-complete');
}



public function paymentError(Request $request)
{
    // Handle payment error

// echo "<pre>";
// print_r($request->paymentId);
// exit();

$order_ids = $request->paymentId;
//return redirect()->route('order-failed'); // Redirect to a failure page

 return view(VIEW_FILE_NAMES['order_failed'], compact('order_ids'));

}
 public function payment(Request $request)
    {

//return view('payment');

return view(VIEW_FILE_NAMES['payment'], [
        ]);

}







public function primeverifyPayment(Request $request)
{
    $paymentId = $request->input('paymentId');

try {
    $paymentId = $request->input('paymentId');
    if($paymentId)
    {
    $response = $this->client->post("https://apitest.myfatoorah.com/v2/GetPaymentStatus", [
        'json' => [
            'Key' => $paymentId,
            'KeyType' => 'PaymentId'
        ],
        'headers' => [
            'Authorization' => 'Bearer rWYRPLYiXuOsqeD7twqNgJ4YYPt4UVo6dBUcqd7GmB6aE-knpoBMTM3YhZHNk3-rHpRqUoXt9svY5UcD7cvQmW1ItWh5E58izrR3B0bl_WdoJpTIeuDBG1oyXFQxTTmHnEXhHA0bIQ5mM7mpoXoN-VOShI63e5nlZzLmGMPxJDDT8aUXr0_EU6Tgb1VuKT_jT1lKe9F_HQr1iF-O9oMyDskRKMZQHsa_e68FvAofyiZ3Vdb4XpJRWNxofisMdPBrYsVkadvKF2MAwGo3B_sncJiv1gqCs4Y6hfFL0-x3rGCR9OJ394D2ri3FakLYEpwgYPu0VdkIuhLOjyBxG3CqUMd3jDV1MFOBYXLlls1kS4NmJQFJ6nCK33RypGOsTppcko_RCX5Wjb6tLFQk8cZB5Zm7oP4JO0pLTmrIxg1cPbY6kOgIR7IfGqkQOYSEEkPR4hAYyLF06pknLmbIYTpOJU-xwzXh_gFSYOEcvi5VsdoBc6Iyl_KHRNSMzTswjFQ53Nbn1buT9TvJ2BIxE7dPK-pKA63YstmIPJmJ6nEEWCX4UIeaLxREUy5XPJYbDyWsmD7YnGJSwX0s1TWKrQOc2xVZTsLu_zOzMs7tInHwJpygkk_KyuWYTF3-nI9h2Cvg7DZcLMMh7U3D40HvmnBalw5IkeOwjt7EzWzRmx3N1n9GAv6e',  // Replace with your actual token
            'Accept'        => 'application/json',
        ],
    ]);

    // Capture and decode the response body
    $responseBody = $response->getBody()->getContents();
    $responseData = json_decode($responseBody, true);

// echo "<pre>";
// print_r($responseData);die;

$status= null;

    // If you need to access TransactionStatus for the first transaction
if (!empty($responseData['Data']['InvoiceTransactions'])) {
    $status = $responseData['Data']['InvoiceTransactions'][0]['TransactionStatus'];
}


$invoiceid= null;

    // If you need to access TransactionStatus for the first transaction
if (!empty($responseData['Data']['InvoiceId'])) {
    $invoiceid = $responseData['Data']['InvoiceId'];
}



    $subdata = PrimeSubscription::where('transaction_ref', $invoiceid)->first();
    if ($subdata) {
        $subdata->payment_status = $status == 'Succss' ? 'paid' : 'failed';
        $subdata->save();



    $userdata = User::where('id', $subdata->customer_id)->first();
    if ($userdata) {
        $userdata->prime_member_status = $status == 'Succss' ? 1 : 0;
        $userdata->save();
    }

    }




}

} catch (\GuzzleHttp\Exception\RequestException $e) {
    // Handle and display the exception
    echo "<pre>";
    echo "Request failed: " . $e->getMessage();
    echo "</pre>";
}




    // Update the order status based on payment status

//}
$order_ids=[];
array_push($order_ids, $invoiceid);
return view(VIEW_FILE_NAMES['subscription_complete'], compact('order_ids'));
   // return redirect()->route('order-complete');
}



public function primepaymentError(Request $request)
{
    // Handle payment error

//  echo "<pre>";
//  print_r($request->all());
//  dd('deepak');
//return redirect()->route('order-failed'); // Redirect to a failure page

$order_ids = $request->paymentId;
 return view(VIEW_FILE_NAMES['order_failed'], compact('order_ids'));

}













public function magzineverifyPayment(Request $request)
{
    $paymentId = $request->input('paymentId');

try {
    $paymentId = $request->input('paymentId');
    if($paymentId)
    {
    $response = $this->client->post("https://apitest.myfatoorah.com/v2/GetPaymentStatus", [
        'json' => [
            'Key' => $paymentId,
            'KeyType' => 'PaymentId'
        ],
        'headers' => [
            'Authorization' => 'Bearer rWYRPLYiXuOsqeD7twqNgJ4YYPt4UVo6dBUcqd7GmB6aE-knpoBMTM3YhZHNk3-rHpRqUoXt9svY5UcD7cvQmW1ItWh5E58izrR3B0bl_WdoJpTIeuDBG1oyXFQxTTmHnEXhHA0bIQ5mM7mpoXoN-VOShI63e5nlZzLmGMPxJDDT8aUXr0_EU6Tgb1VuKT_jT1lKe9F_HQr1iF-O9oMyDskRKMZQHsa_e68FvAofyiZ3Vdb4XpJRWNxofisMdPBrYsVkadvKF2MAwGo3B_sncJiv1gqCs4Y6hfFL0-x3rGCR9OJ394D2ri3FakLYEpwgYPu0VdkIuhLOjyBxG3CqUMd3jDV1MFOBYXLlls1kS4NmJQFJ6nCK33RypGOsTppcko_RCX5Wjb6tLFQk8cZB5Zm7oP4JO0pLTmrIxg1cPbY6kOgIR7IfGqkQOYSEEkPR4hAYyLF06pknLmbIYTpOJU-xwzXh_gFSYOEcvi5VsdoBc6Iyl_KHRNSMzTswjFQ53Nbn1buT9TvJ2BIxE7dPK-pKA63YstmIPJmJ6nEEWCX4UIeaLxREUy5XPJYbDyWsmD7YnGJSwX0s1TWKrQOc2xVZTsLu_zOzMs7tInHwJpygkk_KyuWYTF3-nI9h2Cvg7DZcLMMh7U3D40HvmnBalw5IkeOwjt7EzWzRmx3N1n9GAv6e',  // Replace with your actual token
            'Accept'        => 'application/json',
        ],
    ]);

    // Capture and decode the response body
    $responseBody = $response->getBody()->getContents();
    $responseData = json_decode($responseBody, true);

// echo "<pre>";
// print_r($responseData);die;

$status= null;

    // If you need to access TransactionStatus for the first transaction
if (!empty($responseData['Data']['InvoiceTransactions'])) {
    $status = $responseData['Data']['InvoiceTransactions'][0]['TransactionStatus'];
}


$invoiceid= null;

    // If you need to access TransactionStatus for the first transaction
if (!empty($responseData['Data']['InvoiceId'])) {
    $invoiceid = $responseData['Data']['InvoiceId'];
}



    $subdata = MagzineProduct::where('transaction_ref', $invoiceid)->first();
    if ($subdata) {
        $subdata->payment_status = $status == 'Succss' ? 1 : 0;
        $subdata->save();

    }




}

} catch (\GuzzleHttp\Exception\RequestException $e) {
    // Handle and display the exception
    echo "<pre>";
    echo "Request failed: " . $e->getMessage();
    echo "</pre>";
}




    // Update the order status based on payment status

//}
$order_ids=[];
array_push($order_ids, $invoiceid);
// return view(VIEW_FILE_NAMES['subscription_complete'], compact('order_ids'));


    return redirect()->route('vendor.magzineproducts.list','all')->with('success', 'Payment done successfully. Your invoice id is'.$invoiceid);



}



public function magzinepaymentError(Request $request)
{
 return view(VIEW_FILE_NAMES['order_failed'], compact('order_ids'));

}









}
