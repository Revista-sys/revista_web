@php($overallRating = getOverallRating($product->reviews))



<?php
$authuser= auth('customer')->user()->prime_member_status??'';
 ?>
<div class="product-single-hover style--card">
    <div class="overflow-hidden position-relative">
        <div class=" inline_product clickable d-flex justify-content-center">
            @if( $authuser==1 && $product->discount > 0 && $product->prime_product_status ==0)
                <span class="for-discount-value p-1 pl-2 pr-2 font-bold fs-13">
                    <span class="direction-ltr d-block">
                        @if ($product->discount_type == 'percent')
                            -{{ round($product->discount, (!empty($decimal_point_settings) ? $decimal_point_settings: 0)) }}%
                        @elseif($product->discount_type =='flat')
                            -{{ webCurrencyConverter(amount: $product->discount) }}
                        @endif
                    </span>
                </span>
            @else
                <div class="d-flex justify-content-end">
                    <span class="for-discount-value-null"></span>
                </div>
            @endif
            <div class="p-10px pb-0">
                <a href="{{route('product',$product->slug)}}" class="w-100">
                  

 
                    <img alt="" src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
 

                </a>
            </div>

            <div class="quick-view">
                <a class="btn-circle stopPropagation action-product-quick-view" href="javascript:" data-product-id="{{ $product->id }}">
                    <i class="czi-eye align-middle"></i>
                </a>
            </div>
            @if($product->product_type == 'physical' && $product->current_stock <= 0)
                <span class="out_fo_stock" style="display: none;">{{translate('out_of_stock')}}</span>
            @endif
        </div>
        <div class="single-product-details" style="min-height: 50px;">
            @if($overallRating[0] != 0 )
            <div class="rating-show justify-content-between text-center">
                <span class="d-inline-block font-size-sm text-body">
                    @for($inc=1;$inc<=5;$inc++)
                        @if ($inc <= (int)$overallRating[0])
                            <i class="tio-star text-warning"></i>
                        @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                            <i class="tio-star-half text-warning"></i>
                        @else
                            <i class="tio-star-outlined text-warning"></i>
                        @endif
                    @endfor
                    <label class="badge-style">( {{ count($product->reviews) }} )</label>
                </span>
            </div>
            @endif
            <div class="text-center">
                <a href="{{route('magzinedetails',$product->slug)}}">
                    
 
                    {{ Str::limit($product->name, 23) }}
                </a>
            </div>
            <div class="justify-content-between text-center" style="display: none;">
                <div class="product-price text-center d-flex flex-wrap justify-content-center align-items-center gap-8">


                    @if($authuser==1 && $product->discount > 0 && $product->prime_product_status==0)
                        <del class="category-single-product-price">
                            {{ webCurrencyConverter(amount: $product->unit_price) }}
                        </del>
                        <br>
                    @endif
                    <span class="text-accent text-dark">
                                   @if($authuser==1 && $product->discount > 0 && $product->prime_product_status == 0)


 {{ webCurrencyConverter(amount:
                            $product->unit_price-(getProductDiscount(product: $product, price: $product->unit_price))
                        ) }}
@else 

                        {{ webCurrencyConverter(amount:
                            $product->unit_price)
                         }}

                         @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
