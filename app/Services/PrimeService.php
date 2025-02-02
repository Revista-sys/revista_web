<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class PrimeService
{
    use FileManagerTrait;

    public function getProcessedData(object $request): array
    {
        

        return [
            'name' => $request['name'],
            'price' => $request['price'],
            'type' => $request['plantype'],
            
        ];
    }

    // public function getBannerTypes(): array
    // {
    //     $isReactActive = getWebConfig(name: 'react_setup')['status'];
    //     $bannerTypes = [];
    //     if (theme_root_path() == 'default') {
    //         $bannerTypes = [
    //             "Main Banner" => translate('main_Banner'),
    //             "Popup Banner" => translate('popup_Banner'),
    //             "Footer Banner" => translate('footer_Banner'),
    //             "Main Section Banner" => translate('main_Section_Banner')
    //         ];

    //     }elseif (theme_root_path() == 'theme_aster') {
    //         $bannerTypes = [
    //             "Main Banner" => translate('main_Banner'),
    //             "Popup Banner" => translate('popup_Banner'),
    //             "Footer Banner" => translate('footer_Banner'),
    //             "Main Section Banner" => translate('main_Section_Banner'),
    //             "Header Banner" => translate('header_Banner'),
    //             "Sidebar Banner" => translate('sidebar_Banner'),
    //             "Top Side Banner" => translate('top_Side_Banner'),
    //         ];
    //     }elseif (theme_root_path() == 'theme_fashion') {
    //         $bannerTypes = [
    //             "Main Banner" => translate('main_Banner'),
    //             "Popup Banner" => translate('popup_Banner'),
    //             "Promo Banner Left" => translate('promo_banner_left'),
    //             "Promo Banner Middle Top" => translate('promo_banner_middle_top'),
    //             "Promo Banner Middle Bottom" => translate('promo_banner_middle_bottom'),
    //             "Promo Banner Right" => translate('promo_banner_right'),
    //             "Promo Banner Bottom" => translate('promo_banner_bottom'),
    //         ];
    //     }

    //     if($isReactActive){
    //         $reactBanner = [
    //             'Main Banner' => translate('main_Banner'),
    //             'Main Section Banner' => translate('main_Section_Banner'),
    //             'Top Side Banner' => translate('top_Side_Banner'),
    //             'Footer Banner' => translate('footer_Banner'),
    //             'Popup Banner' => translate('popup_Banner'),
    //         ];
    //         $bannerTypes = array_unique(array_merge($bannerTypes, $reactBanner));
    //     }

    //     return $bannerTypes;
    // }

}
