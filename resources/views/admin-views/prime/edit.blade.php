@extends('layouts.back-end.app')

@section('title', 'Plan Details Update')

@section('content')
    <div class="content container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <div>
                <h2 class="h1 mb-1 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/banner.png') }}" alt="">
                    {{ 'Plan Update Form' }}
                </h2>
            </div>
            <div>
                <a class="btn btn--primary text-white" href="{{ route('admin.prime.list') }}">
                    <i class="tio-chevron-left"></i> {{ translate('back') }}</a>
            </div>
        </div>

        <div class="row text-start">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form  method="post" enctype="multipart/form-data"
                              class="prime_form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="id" name="id">
                                    </div>


                                    <div class="form-group mb-3">
                                        <label for="name" class="title-color text-capitalize">{{ 'Name' }}</label>
                                        <input type="text" name="name" class="form-control" id="name" required placeholder="{{ 'Name' }}" value={{$primes['name']}}>
                                    </div>


                                     <div class="form-group mb-3">
                                        <label for="price" class="title-color text-capitalize">{{ 'Price' }}</label>
                                        <input type="text" name="price" class="form-control" id="price" required placeholder="{{ 'Price' }}" value={{$primes['price']}}>
                                    </div>

                                  
                                      <div class="form-group mb-3">
                                        <label for="plantype" class="title-color text-capitalize">{{ 'Plan Type' }}</label>
                                      

                                       <select class="form-control" name="plantype" id="plantype">
                                        <option  {{$primes['type']==1?'selected':""}} value="1"> Monthly</option>
                                        <option {{$primes['type']==2?'selected':""}} value="2">6 Months</option>
                                        <option {{$primes['type']==3?'selected':""}} value="3"> Yearly</option>
                                       </select>

                                    </div>
                                 

                                    
                                    
                                </div>
                            

                                <div class="col-md-12 d-flex justify-content-end gap-3">
                                    <button type="reset" class="btn btn-secondary px-4">{{ translate('reset') }}</button>
                                    <button type="submit" class="btn btn--primary px-4">{{ translate('update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/banner.js') }}"></script>
    <script>
        "use strict";
        $(document).on('ready', function () {
            getThemeWiseRatio();
        });
        let elementBannerTypeSelect = $('#banner_type_select');
        elementBannerTypeSelect.on('change',function(){
            getThemeWiseRatio();
        });
        function getThemeWiseRatio(){
            let bannerType = elementBannerTypeSelect.val();
            let theme = '{{ theme_root_path() }}';
            let themeRatio = {!! json_encode(THEME_RATIO) !!};
            let getRatio = themeRatio[theme][bannerType];
            $('#theme_ratio').text(getRatio);
        }
    </script>
@endpush
