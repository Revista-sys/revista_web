<?php

namespace App\Services;

use GuzzleHttp\Client;

class MyFatoorahService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('MYFATOORAH_API_KEY');
        $this->apiUrl = env('MYFATOORAH_API_URL');
        $this->client = new Client();
    }

    public function createPayment($amount, $currency, $invoiceId, $description)
    {
        try {
            $response = $this->client->post('https://apitest.myfatoorah.com/v2/SendPayment', [
                'json' => [
                  'InvoiceValue' => $amount,
                  'DisplayCurrencyIso' => $currency,
                  "CustomerName"=>"deepakgoud",
                  "CustomerEmail"=>"goud.deepak@gmail.com",
                  "CustomerMobile"=>"1234567890",
                  'Description' => $description,
                   "CallbackUrl"=> "http://localhost/revista/verify-payment",
                   "ErrorUrl"=> "http://localhost/revista/payment-error",
                   'Language' => 'en',
                   "NotificationOption"=> "ALL"
                ],
                'headers' => [
                    'Authorization' => 'Bearer  rWYRPLYiXuOsqeD7twqNgJ4YYPt4UVo6dBUcqd7GmB6aE-knpoBMTM3YhZHNk3-rHpRqUoXt9svY5UcD7cvQmW1ItWh5E58izrR3B0bl_WdoJpTIeuDBG1oyXFQxTTmHnEXhHA0bIQ5mM7mpoXoN-VOShI63e5nlZzLmGMPxJDDT8aUXr0_EU6Tgb1VuKT_jT1lKe9F_HQr1iF-O9oMyDskRKMZQHsa_e68FvAofyiZ3Vdb4XpJRWNxofisMdPBrYsVkadvKF2MAwGo3B_sncJiv1gqCs4Y6hfFL0-x3rGCR9OJ394D2ri3FakLYEpwgYPu0VdkIuhLOjyBxG3CqUMd3jDV1MFOBYXLlls1kS4NmJQFJ6nCK33RypGOsTppcko_RCX5Wjb6tLFQk8cZB5Zm7oP4JO0pLTmrIxg1cPbY6kOgIR7IfGqkQOYSEEkPR4hAYyLF06pknLmbIYTpOJU-xwzXh_gFSYOEcvi5VsdoBc6Iyl_KHRNSMzTswjFQ53Nbn1buT9TvJ2BIxE7dPK-pKA63YstmIPJmJ6nEEWCX4UIeaLxREUy5XPJYbDyWsmD7YnGJSwX0s1TWKrQOc2xVZTsLu_zOzMs7tInHwJpygkk_KyuWYTF3-nI9h2Cvg7DZcLMMh7U3D40HvmnBalw5IkeOwjt7EzWzRmx3N1n9GAv6e'
                ]
            ]);

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            // Handle errors
            return ['error' => $e->getMessage()];
        }
    }

    public function verifyPayment($paymentId)
    {
        try {
            $response = $this->client->get('https://apitest.myfatoorah.com/v2/SendPayment/'.$paymentId, [
                'headers' => [
                    'Authorization' => 'Bearer rWYRPLYiXuOsqeD7twqNgJ4YYPt4UVo6dBUcqd7GmB6aE-knpoBMTM3YhZHNk3-rHpRqUoXt9svY5UcD7cvQmW1ItWh5E58izrR3B0bl_WdoJpTIeuDBG1oyXFQxTTmHnEXhHA0bIQ5mM7mpoXoN-VOShI63e5nlZzLmGMPxJDDT8aUXr0_EU6Tgb1VuKT_jT1lKe9F_HQr1iF-O9oMyDskRKMZQHsa_e68FvAofyiZ3Vdb4XpJRWNxofisMdPBrYsVkadvKF2MAwGo3B_sncJiv1gqCs4Y6hfFL0-x3rGCR9OJ394D2ri3FakLYEpwgYPu0VdkIuhLOjyBxG3CqUMd3jDV1MFOBYXLlls1kS4NmJQFJ6nCK33RypGOsTppcko_RCX5Wjb6tLFQk8cZB5Zm7oP4JO0pLTmrIxg1cPbY6kOgIR7IfGqkQOYSEEkPR4hAYyLF06pknLmbIYTpOJU-xwzXh_gFSYOEcvi5VsdoBc6Iyl_KHRNSMzTswjFQ53Nbn1buT9TvJ2BIxE7dPK-pKA63YstmIPJmJ6nEEWCX4UIeaLxREUy5XPJYbDyWsmD7YnGJSwX0s1TWKrQOc2xVZTsLu_zOzMs7tInHwJpygkk_KyuWYTF3-nI9h2Cvg7DZcLMMh7U3D40HvmnBalw5IkeOwjt7EzWzRmx3N1n9GAv6e'
                ]
            ]);

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            // Handle errors
            return ['error' => $e->getMessage()];
        }
    }
}
