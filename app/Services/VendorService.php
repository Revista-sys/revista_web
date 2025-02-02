<?php

namespace App\Services;

use App\Traits\FileManagerTrait;
use Illuminate\Support\Str;

class VendorService
{
    use FileManagerTrait;
    /**
     * @param string $email
     * @param string $password
     * @param string|bool|null $rememberToken
     * @return bool
     */
    public function isLoginSuccessful(string $email, string $password, string|null|bool $rememberToken): bool
    {
        if (auth('seller')->attempt(['email' => $email, 'password' => $password], $rememberToken)) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getInitialWalletData(int $vendorId): array
    {
        return [
            'seller_id' => $vendorId,
            'withdrawn' => 0,
            'commission_given' => 0,
            'total_earning' => 0,
            'pending_withdraw' => 0,
            'delivery_charge_earned' => 0,
            'collected_cash' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function logout(): void
    {
        auth()->guard('seller')->logout();
        session()->invalidate();
    }

    /**
     * @param object $request
     * @return array
     */
    public function getFreeDeliveryOverAmountData(object $request):array
    {
        return [
            'free_delivery_status' => $request['free_delivery_status'] == 'on' ? 1 : 0,
            'free_delivery_over_amount' => currencyConverter($request['free_delivery_over_amount'], 'usd'),
        ];
    }

    /**
     * @return array[minimum_order_amount: float|int]
     */
    public function getMinimumOrderAmount(object $request) :array
    {
        return [
            'minimum_order_amount' => currencyConverter($request['minimum_order_amount'], 'usd')
        ];
    }

    /**
     * @param object $request
     * @param object $vendor
     * @return array
     */
    public function getVendorDataForUpdate(object $request, object $vendor):array
    {


        $image = $request['image'] ? $this->update(dir: 'seller/', oldImage: $vendor['image'], format: 'webp', image: $request->file('image')) : $vendor['image'];

        $docimage = $request['docimage'] ? $this->update(dir: 'seller/', oldImage: $vendor['docimage'], format: 'webp', image: $request->file('docimage')) : $vendor['docimage'];

            $docimage1 = $request['docimage1'] ? $this->update(dir: 'seller/', oldImage: $vendor['docimage1'], format: 'webp', image: $request->file('docimage1')) : $vendor['docimage1'];

        $iban_certificate = $request['iban_certificate'] ? $this->update(dir: 'seller/', oldImage: $vendor['iban'], format: 'webp', image: $request->file('iban_certificate')) : $vendor['iban'];

          $vat_certificate = $request['vat_certificate'] ? $this->update(dir: 'seller/', oldImage: $vendor['vat'], format: 'webp', image: $request->file('vat_certificate')) : $vendor['vat'];



           $free_certificate = $request['free_certificate'] ? $this->update(dir: 'seller/', oldImage: $vendor['freelancer'], format: 'webp', image: $request->file('free_certificate')) : $vendor['freelancer'];


 $fda_certificate = $request['fda_certificate'] ? $this->update(dir: 'seller/', oldImage: $vendor['fda'], format: 'webp', image: $request->file('fda_certificate')) : $vendor['fda'];


          $commercial_registration = $request['commercial_registration'];

          $national_address = $request['national_address'];



        return [
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'phone' => $request['phone'],
            'image' => $image,
            'doc_image' => $docimage,
            'doc_image1' => $docimage1,
            'iban' => $iban_certificate,
            'vat' => $vat_certificate,
             'fda' => $fda_certificate,
             'freelancer' => $free_certificate,
             'commercial_registration' => $commercial_registration,
             'national_address' => $national_address,
             'iban_number' => $request['iban_number'],
             'bank_id' => $request['bank_id'],
             'account_holder' => $request['account_holder'],
             'bank_account' => $request['bank_account'],
        ];





    }

    /**
     * @return array[password: string]
     */
    public function getVendorPasswordData(object $request):array
    {
        return [
            'password' => bcrypt($request['password']),
        ];
    }

    /**
     * @param object $request
     * @return array
     */
    public function getVendorBankInfoData(object $request):array
    {
        return [
            'bank_name' => $request['bank_name'],
            'branch' => $request['branch'],
            'holder_name' => $request['holder_name'],
            'account_no' => $request['account_no'],
        ];
    }
    public function getAddData(object $request):array
    {

// print_r($request);die;

        return [
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'seller_type' =>$request['merchant_type'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'image' => $this->upload(dir: 'seller/', format: 'webp', image: $request->file('image')),
            'password' => bcrypt($request['password']),
            'status' => $request['status'] == 'approved' ? 'approved' : 'pending',
              'doc_image' => $this->upload(dir: 'seller/', format: 'webp', image: $request->file('docimage')),
            'doc_image1' => $this->upload(dir: 'seller/', format: 'webp', image: $request->file('docimage1'))

        ];
    }
}
