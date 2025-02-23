@extends('layouts.front-end.app')

@section('title', translate('All_Magzine_Page'))

@push('css_or_js')
    <meta property="og:image" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Brands of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <meta property="twitter:card" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Brands of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
@endpush

@section('content')

    <div class="container mb-md-4 {{Session::get('direction') === "rtl" ? 'rtl' : ''}} __inline-65">
        <div class="bg-primary-light rounded-10 my-4 p-3 p-sm-4" data-bg-img="{{ theme_asset(path: 'public/assets/front-end/img/media/bg.png') }}">
            <div class="row g-2 align-items-center">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex flex-column gap-1 text-primary">
                        <h4 class="mb-0 text-start fw-bold text-primary text-uppercase">Magazine Categorys</h4>
                        <p class="fs-14 fw-semibold mb-0">{{translate('Find_your_desired_stores_and_shop_your_favourite_products')}}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" style="display: none;">
                    <form action="{{ route('vendors') }}">
                        <div class="input-group">
                            <input type="text" class="form-control rounded-10" value="{{request('shop_name')}}"  placeholder="{{translate('Search_Store')}}" name="shop_name">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary rounded-10" type="submit">{{translate('search')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-12">
                @if(count($sellers) > 0)
                    <div class="row mx-n2 __min-h-200px">
                        @foreach ($sellers as $seller)
                            @php($current_date = date('Y-m-d'))
                            @php($start_date = date('Y-m-d', strtotime($seller['vacation_start_date'])))
                            @php($end_date = date('Y-m-d', strtotime($seller['vacation_end_date'])))
                            <div class="col-lg-3 col-md-6 col-sm-12 px-2 pb-4 text-center" style="display:none;">
                                <a href="{{route('shopView',['id'=>$seller['id']])}}" class="others-store-card text-capitalize">
                                    <div class="overflow-hidden other-store-banner">
                                        @if($seller['id'] != 0)
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'storage/app/public/shop/banner/'.$seller['banner'], type: 'shop-banner') }}">
                                        @else
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'storage/app/public/shop/'.$seller['banner'], type: 'shop-banner') }}">
                                        @endif
                                    </div>
                                    <div class="name-area">
                                        <div class="position-relative" style="display:none">
                                            <div class="overflow-hidden other-store-logo rounded-full">
                                                @if($seller['id'] != 0)
                                                    <img class="rounded-full" alt="{{ translate('store') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/shop/'.$seller['image'], type: 'shop') }}">
                                                @else
                                                <img class="rounded-full" alt="{{ translate('store') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/company/'.$seller['image'], type: 'shop') }}">
                                                @endif
                                            </div>

                                            @if($seller['temporary_close'] || ($seller['vacation_status'] && ($current_date >= $seller['vacation_start_date']) && ($current_date <= $seller['vacation_end_date'])))
                                                <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="info pt-4 pl-4">
                                            <h5 class="text-start">{{ $seller['name'] }}</h5>
                                            <div class="d-flex align-items-center">
                                                <h6 class="web-text-primary">{{number_format($seller['average_rating'],1)}}</h6>
                                                <i class="tio-star text-star mx-1"></i>
                                                <small>{{ translate('rating') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info-area">
                                        <div class="info-item">
                                            <h6 class="web-text-primary">{{$seller['review_count'] < 1000 ? $seller['review_count'] : number_format($seller['review_count']/1000 , 1).'K'}}</h6>
                                            <span>{{ translate('reviews') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <h6 class="web-text-primary">{{$seller['products_count'] < 1000 ? $seller['products_count'] : number_format($seller['products_count']/1000 , 1).'K'}}</h6>
                                            <span>{{ translate('magzine') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach


                        <div class="col-lg-3 col-md-6 col-sm-12 px-2 pb-4 text-center">
                                <a href="{{route('magzineView',['id'=>1])}}" class="others-store-card text-capitalize">
                                    <div class="overflow-hidden other-store-banner">
                                        @if($seller['id'] != 0)
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'public/magzine/2024-05-16-6645e07f000e2.jpg', type: 'shop-banner') }}">
                                        @else
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'public/magzine/2024-05-16-6645e07f000e2.jpg', type: 'shop-banner') }}">
                                        @endif
                                    </div>
                                    <div class="name-area">
                                        <div class="position-relative" style="display:none">
                                            <div class="overflow-hidden other-store-logo rounded-full">
                                                @if($seller['id'] != 0)
                                                    <img class="rounded-full" alt="{{ translate('store') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/magzine/2024-05-16-6645b60c33c9b.webp', type: 'shop') }}">
                                                @else
                                                <img class="rounded-full" alt="{{ translate('store') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/magzine/banner/2024-05-16-6645b5c694e73.webp', type: 'shop') }}">
                                                @endif
                                            </div>

                                            @if($seller['temporary_close'] || ($seller['vacation_status'] && ($current_date >= $seller['vacation_start_date']) && ($current_date <= $seller['vacation_end_date'])))
                                                <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="info pt-4 pl-4">
                                            <h5 class="text-start">{{ 'Educational Courses' }}</h5>
                                            <div class="d-flex align-items-center">
                                                <h6 class="web-text-primary">{{number_format(2.5,1)}}</h6>
                                                <i class="tio-star text-star mx-1"></i>
                                                <small>{{ translate('rating') }}</small>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="info-area">
                                        <div class="info-item">
                                            <h6 class="web-text-primary">3</h6>
                                            <span>{{ translate('reviews') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <h6 class="web-text-primary">5</h6>
                                            <span>{{ translate('magzine') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12 px-2 pb-4 text-center">
                                <a href="{{route('magzineView',['id'=>2])}}" class="others-store-card text-capitalize">
                                    <div class="overflow-hidden other-store-banner">
                                        @if($seller['id'] != 0)
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'public/magzine/business_category.jpg', type: 'shop-banner') }}">
                                        @else
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'public/magzine/business_category.jpg', type: 'shop-banner') }}">
                                        @endif
                                    </div>
                                    <div class="name-area">
                                        <div class="position-relative" style="display:none">
                                            <div class="overflow-hidden other-store-logo rounded-full">
                                                @if($seller['id'] != 0)
                                                    <img class="rounded-full" alt="{{ translate('store') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/magzine/business_category.jpg', type: 'shop') }}">
                                                @else
                                                <img class="rounded-full" alt="{{ translate('store') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/magzine/2024-05-16-6645b5c694e73.webp', type: 'shop') }}">
                                                @endif
                                            </div>


                                            @if($seller['temporary_close'] || ($seller['vacation_status'] && ($current_date >= $seller['vacation_start_date']) && ($current_date <= $seller['vacation_end_date'])))
                                                <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="info pt-4 pl-4">
                                            <h5 class="text-start">{{ 'Training Courses' }}</h5>
                                            <div class="d-flex align-items-center">
                                                <h6 class="web-text-primary">{{number_format(2.5,1)}}</h6>
                                                <i class="tio-star text-star mx-1"></i>
                                                <small>{{ translate('rating') }}</small>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="info-area">
                                        <div class="info-item">
                                            <h6 class="web-text-primary">3</h6>
                                            <span>{{ translate('reviews') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <h6 class="web-text-primary">5</h6>
                                            <span>{{ translate('magzine') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12 px-2 pb-4 text-center">
                                <a href="{{route('magzineView',['id'=>3])}}" class="others-store-card text-capitalize">
                                    <div class="overflow-hidden other-store-banner">
                                        @if($seller['id'] != 0)
                                            <img class="w-100 h-1
                                            00 object-cover" alt="" src="{{ getValidImage(path: 'public/magzine/perfume_category.jpg', type: 'shop-banner') }}">
                                        @else
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'public/magzine/perfume_category.jpg', type: 'shop-banner') }}">
                                        @endif
                                    </div>
                                    <div class="name-area">
                                        <div class="position-relative" style="display:none">
                                            

                                             <div class="overflow-hidden other-store-logo rounded-full">
                                                @if($seller['id'] != 0)
                                                    <img class="rounded-full" alt="{{ translate('store') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/magzine/2024-05-16-6645b72f3b436.webp', type: 'shop') }}">
                                                @else
                                                <img class="rounded-full" alt="{{ translate('store') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/magzine/banner/2024-05-16-6645b5c694e73.webp', type: 'shop') }}">
                                                @endif
                                            </div>

                                            @if($seller['temporary_close'] || ($seller['vacation_status'] && ($current_date >= $seller['vacation_start_date']) && ($current_date <= $seller['vacation_end_date'])))
                                                <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="info pt-4 pl-4">
                                            <h5 class="text-start">{{ 'Perfumes' }}</h5>
                                            <div class="d-flex align-items-center">
                                                <h6 class="web-text-primary">{{number_format(2.5,1)}}</h6>
                                                <i class="tio-star text-star mx-1"></i>
                                                <small>{{ translate('rating') }}</small>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="info-area">
                                        <div class="info-item">
                                            <h6 class="web-text-primary">3</h6>
                                            <span>{{ translate('reviews') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <h6 class="web-text-primary">5</h6>
                                            <span>{{ translate('magzine') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12 px-2 pb-4 text-center">
                                <a href="{{route('magzineView',['id'=>4])}}" class="others-store-card text-capitalize">
                                    <div class="overflow-hidden other-store-banner">
                                        @if($seller['id'] != 0)
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'public/magzine/food_truck.webp', type: 'shop-banner') }}">
                                        @else
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'public/magzine/food_truck.webp', type: 'shop-banner') }}">
                                        @endif
                                    </div>
                                    <div class="name-area">
                                        <div class="position-relative" style="display:none">
                                            <div class="overflow-hidden other-store-logo rounded-full">
                                                @if($seller['id'] != 0)
                                                    <img class="rounded-full" alt="{{ translate('store') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/shop/'.$seller['image'], type: 'shop') }}">
                                                @else
                                                <img class="rounded-full" alt="{{ translate('store') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/company/'.$seller['image'], type: 'shop') }}">
                                                @endif
                                            </div>

                                            @if($seller['temporary_close'] || ($seller['vacation_status'] && ($current_date >= $seller['vacation_start_date']) && ($current_date <= $seller['vacation_end_date'])))
                                                <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="info pt-4 pl-4">
                                            <h5 class="text-start">{{ 'Food Truck' }}</h5>
                                            <div class="d-flex align-items-center">
                                                <h6 class="web-text-primary">{{number_format(2.5,1)}}</h6>
                                                <i class="tio-star text-star mx-1"></i>
                                                <small>{{ translate('rating') }}</small>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="info-area">
                                        <div class="info-item">
                                            <h6 class="web-text-primary">3</h6>
                                            <span>{{ translate('reviews') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <h6 class="web-text-primary">5</h6>
                                            <span>{{ translate('magzine') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>




<div class="col-lg-3 col-md-6 col-sm-12 px-2 pb-4 text-center">
                                <a href="{{route('magzineView',['id'=>5])}}" class="others-store-card text-capitalize">
                                    <div class="overflow-hidden other-store-banner">
                                        @if($seller['id'] != 0)
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'public/magzine/exclusive_category.jpg', type: 'shop-banner') }}">
                                        @else
                                            <img class="w-100 h-100 object-cover" alt="" src="{{ getValidImage(path: 'public/magzine/exclusive_category.jpg', type: 'shop-banner') }}">
                                        @endif
                                    </div>
                                    <div class="name-area">
                                        <div class="position-relative" style="display:none">
                                            <div class="overflow-hidden other-store-logo rounded-full">
                                                @if($seller['id'] != 0)
                                                    <img class="rounded-full" alt="{{ translate('store') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/shop/'.$seller['image'], type: 'shop') }}">
                                                @else
                                                <img class="rounded-full" alt="{{ translate('store') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/company/'.$seller['image'], type: 'shop') }}">
                                                @endif
                                            </div>

                                            @if($seller['temporary_close'] || ($seller['vacation_status'] && ($current_date >= $seller['vacation_start_date']) && ($current_date <= $seller['vacation_end_date'])))
                                                <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="info pt-4 pl-4">
                                            <h5 class="text-start">{{ 'Exclusive Products' }}</h5>
                                            <div class="d-flex align-items-center">
                                                <h6 class="web-text-primary">{{number_format(2.5,1)}}</h6>
                                                <i class="tio-star text-star mx-1"></i>
                                                <small>{{ translate('rating') }}</small>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="info-area">
                                        <div class="info-item">
                                            <h6 class="web-text-primary">3</h6>
                                            <span>{{ translate('reviews') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <h6 class="web-text-primary">5</h6>
                                            <span>{{ translate('magzine') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>


                    </div>

                    <div class="row mx-n2">
                        <div class="col-md-12">
                            <div class="text-center">
                                {{ $sellers->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-5 text-center text-muted">
                        <div class="d-flex justify-content-center my-2">
                            <img alt="" src="{{ theme_asset(path: 'public/assets/front-end/img/media/seller.svg') }}">
                        </div>
                        <h4 class="text-muted">{{ translate('vendor_not_available') }}</h4>
                        <p>{{ translate('Sorry_no_data_found_related_to_your_search') }}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
