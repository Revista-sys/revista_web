@extends('layouts.front-end.app')

@section('title', translate('choose_Payment_Method'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/payment.css') }}">
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@section('content')
    <div class="container pb-5 mb-2 mb-md-4 rtl px-0 px-md-3 text-align-direction">
        <div class="row mx-max-md-0">
            <div class="col-md-12 mb-3 pt-3 px-max-md-0">
                <div class="feature_header px-3 px-md-0">
                    <span>{{ translate('payment_method') }}</span>
                </div>
            </div>
            <section class="col-lg-8 px-max-md-0">
                <div class="checkout_details">
                    <div class="px-3 px-md-0">
                        @include('web-views.partials._checkout-steps', ['step' => 3])
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">

                            <div class="gap-2 mb-4">
                                <div class="d-flex justify-content-between">
                                    <h4 class="mb-2 text-nowrap">{{ translate('payment_method') }}</h4>
                                    <a href="{{ route('checkout-details') }}"
                                        class="d-flex align-items-center gap-2 text-primary font-weight-bold text-nowrap">
                                        <i class="tio-back-ui fs-12 text-capitalize"></i>
                                        {{ translate('go_back') }}
                                    </a>
                                </div>
                                <p class="text-capitalize mt-2">{{ translate('select_a_payment_method_to_proceed') }}</p>
                            </div>
                            @if (($cashOnDeliveryBtnShow && $cash_on_delivery['status']) || $digital_payment['status'] == 1)
                                <div class="d-flex flex-wrap gap-3 mb-5">
                                    @if ($cashOnDeliveryBtnShow && $cash_on_delivery['status'])
                                        <div id="cod-for-cart" class="payment-method-container">

                                            <div class="card cursor-pointer mb-2">
                                                <form action="{{ route('checkout-complete') }}" method="get"
                                                    class="needs-validation" id="cash_on_delivery_form_cod">
                                                    <label class="m-0">
                                                        <span
                                                            class="btn btn-block click-if-alone d-flex gap-2 align-items-center cursor-pointer"
                                                            style="padding: 0px;">
                                                            <div class="card-cursor-container">
                                                                <div class="card-cursor-container-inner"
                                                                    style="border: 1px solid black; border-radius: 5px; padding: 4px;">
                                                                    <svg width="44" viewBox="0 0 41 13" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M26.4421 9.33218V3.1199H27.4956V3.61802C27.8162 3.21723 28.2915 2.99963 28.8811 2.99963C30.095 2.99963 30.9652 3.96725 30.9652 5.35282C30.9652 6.73838 30.0893 7.72324 28.8811 7.72324C28.2857 7.72324 27.8162 7.49418 27.4956 7.09339V9.33209L26.4421 9.33218ZM27.4957 4.78039V5.92551C27.7189 6.38932 28.1197 6.72709 28.698 6.72709C29.3966 6.72709 29.8661 6.20604 29.8661 5.34722C29.8661 4.49986 29.3966 3.9845 28.698 3.9845C28.1197 3.99018 27.7189 4.30511 27.4957 4.78039Z"
                                                                            fill="#02AA7C" />
                                                                        <path
                                                                            d="M31.6443 6.29642C31.6443 5.54062 32.1138 5.01388 32.8925 4.91655L34.6846 4.68749V4.4642C34.6846 4.19514 34.5472 4.05195 34.2953 4.05195H32.0451V3.09579H34.3354C35.2458 3.09579 35.7439 3.54236 35.7439 4.33256V7.5675H34.6789V7.0236C34.3698 7.45879 33.8602 7.70492 33.2017 7.70492C32.2742 7.70492 31.6443 7.13809 31.6443 6.29642ZM33.4593 6.84601C33.9975 6.84601 34.4956 6.54255 34.6788 6.09598V5.53484L33.3104 5.72382C32.9326 5.77537 32.7207 5.97572 32.7207 6.30211C32.7208 6.65135 32.9898 6.84601 33.4593 6.84601Z"
                                                                            fill="#02AA7C" />
                                                                        <path
                                                                            d="M36.5151 8.3577H37.2823C37.5743 8.3577 37.7061 8.23744 37.8034 7.9626L37.9408 7.5675L36.3147 3.09579H37.4141L38.4275 6.21056H38.4504L39.418 3.09579H40.5001L38.7195 8.23166C38.479 8.93591 38.1583 9.31377 37.374 9.31377H36.5152L36.5151 8.3577Z"
                                                                            fill="#02AA7C" />
                                                                        <path
                                                                            d="M4.36479 12.3801C5.59009 12.3801 6.5978 11.9965 7.26196 11.3552C7.76008 10.8628 8.05207 10.1986 8.05207 9.43146C8.05207 8.73868 7.79439 8.11461 7.3192 7.63933C6.84401 7.16406 6.16261 6.81482 5.30379 6.64878L3.89529 6.37394C3.3113 6.26515 2.97922 5.97316 2.97922 5.56668C2.97922 5.03416 3.4945 4.66777 4.31901 4.66777C4.83429 4.66777 5.27517 4.83381 5.56716 5.1258C5.75035 5.32624 5.87631 5.58383 5.91639 5.87582L7.94896 5.41779C7.89172 4.83381 7.61688 4.31844 7.19894 3.89481C6.52909 3.23634 5.50414 2.81839 4.29608 2.81839C3.17958 2.81839 2.24635 3.18487 1.59935 3.76886C1.04967 4.28413 0.740527 4.97691 0.740527 5.74987C0.740527 6.4255 0.958121 6.99802 1.399 7.43321C1.83988 7.87408 2.4811 8.20048 3.32277 8.40651L4.71411 8.73859C5.41267 8.90463 5.72182 9.16231 5.72182 9.62035C5.72182 10.1872 5.20655 10.5193 4.36488 10.5193C3.75796 10.5193 3.26553 10.3188 2.95638 9.98674C2.73879 9.76914 2.60715 9.47146 2.5899 9.14507L0.5 9.60319C0.557243 10.2273 0.849235 10.7769 1.29011 11.2178C1.9829 11.9449 3.10518 12.3801 4.36479 12.3801ZM19.8353 12.3801C21.1923 12.3801 22.2344 11.8877 22.95 11.1892C23.5168 10.6395 23.8661 9.99829 24.0493 9.33982L21.908 8.62411C21.8163 8.95619 21.6331 9.30542 21.3411 9.57457C20.9919 9.90665 20.5166 10.1414 19.8353 10.1414C19.2112 10.1414 18.6273 9.90096 18.2035 9.48293C17.7798 9.04205 17.5222 8.40083 17.5222 7.59347C17.5222 6.76896 17.7799 6.14488 18.2035 5.70401C18.6215 5.28028 19.1941 5.06278 19.8182 5.06278C20.4766 5.06278 20.9347 5.28037 21.2668 5.61245C21.5416 5.88729 21.7076 6.23653 21.8164 6.58576L23.9979 5.85289C23.8318 5.21166 23.4826 4.57035 22.973 4.03792C22.2401 3.3222 21.1752 2.81261 19.7667 2.81261C18.467 2.81261 17.2933 3.30505 16.4516 4.1524C15.6099 5.01122 15.0946 6.20791 15.0946 7.59925C15.0946 8.99059 15.6272 10.1815 16.486 11.0461C17.3218 11.8877 18.5127 12.3801 19.8353 12.3801ZM12.4321 12.3801C13.3826 12.3801 14.0639 12.0881 14.3559 11.8304V9.84941C14.1326 10.0155 13.6974 10.2159 13.1249 10.2159C12.7184 10.2159 12.4264 10.1242 12.2089 9.9239C12.0257 9.7407 11.934 9.42578 11.934 9.00783V0.619873H9.51212V3.09332H14.3502V5.44081H9.51212V9.60328C9.51212 10.4449 9.7698 11.1263 10.2278 11.6015C10.7431 12.1053 11.4988 12.3801 12.4321 12.3801Z"
                                                                            fill="black" />
                                                                    </svg>
                                                                </div>
                                                                <input type="radio" id="online" class="custom-radio">
                                                            </div>
                                                        </span>
                                                    </label>
                                                </form>
                                            </div>
                                            <div class="card cursor-pointer mb-2">
                                                <form action="{{ route('checkout-complete') }}" method="get"
                                                    class="needs-validation" id="cash_on_delivery_form_cod">
                                                    <label class="m-0">
                                                        <span
                                                            class="btn btn-block click-if-alone d-flex gap-2 align-items-center cursor-pointer"
                                                            style="padding: 0px;">
                                                            <div class="card-cursor-container">
                                                                <div class="card-cursor-container-inner">
                                                                    <img src="{{ theme_asset(path: 'public/assets/front-end/img/pay_logos/benefit_pay.png') }}"
                                                                        alt=""
                                                                        style="width: 55px; height: 48px;">Benefit

                                                                </div>
                                                                <input type="radio" id="online" class="custom-radio">
                                                            </div>
                                                        </span>
                                                    </label>
                                                </form>
                                            </div>
                                            <div class="card cursor-pointer mb-2">
                                                <form action="{{ route('checkout-complete') }}" method="get"
                                                    class="needs-validation" id="cash_on_delivery_form_cod">
                                                    <label class="m-0">
                                                        <span
                                                            class="btn btn-block click-if-alone d-flex gap-2 align-items-center cursor-pointer">
                                                            <div class="card-cursor-container">
                                                                <div class="card-cursor-container-inner">
                                                                    <img src="{{ theme_asset(path: 'public/assets/front-end/img/pay_logos/knet_pay.png') }}"
                                                                        alt="" style="width: 55px; height: 30px;">
                                                                    Knet

                                                                </div>
                                                                <input type="radio" id="online" class="custom-radio">
                                                            </div>
                                                        </span>
                                                    </label>
                                                </form>
                                            </div>
                                            <div class="card cursor-pointer mb-2">
                                                <form action="{{ route('checkout-complete') }}" method="get"
                                                    class="needs-validation" id="cash_on_delivery_form_cod">
                                                    <label class="m-0">
                                                        <span
                                                            class="btn btn-block click-if-alone d-flex gap-2 align-items-center cursor-pointer">
                                                            <div class="card-cursor-container">
                                                                <div class="card-cursor-container-inner">
                                                                    <img src="{{ theme_asset(path: 'public/assets/front-end/img/pay_logos/american_pay.png') }}"
                                                                        alt="" style="width: 55px; height: 30px; ">
                                                                    American Express
                                                                </div>
                                                                <input type="radio" id="online" class="custom-radio">
                                                            </div>
                                                        </span>
                                                    </label>
                                                </form>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($digital_payment['status'] == 1)
                                        @if (auth('customer')->check() && $wallet_status == 1)
                                            <div>
                                                <div class="card cursor-pointer">
                                                    <button
                                                        class="btn btn-block click-if-alone d-flex gap-2 align-items-center"
                                                        type="submit" data-toggle="modal"
                                                        data-target="#wallet_submit_button">
                                                        <img width="20"
                                                            src="{{ theme_asset(path: 'public/assets/front-end/img/icons/wallet-sm.png') }}"
                                                            alt="" />
                                                        <span class="fs-12">{{ translate('pay_via_Wallet') }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endif

                            <div style="display:none!important" class="d-flex flex-wrap gap-2 align-items-center mb-4 ">
                                <h5 class="mb-0 text-capitalize">{{ translate('pay_via_online') }}</h5>
                                <span
                                    class="fs-10 text-capitalize mt-1">({{ translate('faster_&_secure_way_to_pay') }})</span>
                            </div>

                            @if ($digital_payment['status'] == 1)
                                <div class="row gx-4 mb-4">
                                    @foreach ($payment_gateways_list as $payment_gateway)
                                        <div class="col-sm-6">
                                            <form method="post" class="digital_payment"
                                                id="{{ $payment_gateway->key_name }}_form"
                                                action="{{ route('customer.web-payment-request') }}">
                                                @csrf
                                                <input type="hidden" name="user_id"
                                                    value="{{ auth('customer')->check() ? auth('customer')->user()->id : session('guest_id') }}">
                                                <input type="hidden" name="customer_id"
                                                    value="{{ auth('customer')->check() ? auth('customer')->user()->id : session('guest_id') }}">
                                                <input type="hidden" name="payment_method"
                                                    value="{{ $payment_gateway->key_name }}">
                                                <input type="hidden" name="payment_platform" value="web">

                                                @if ($payment_gateway->mode == 'live' && isset($payment_gateway->live_values['callback_url']))
                                                    <input type="hidden" name="callback"
                                                        value="{{ $payment_gateway->live_values['callback_url'] }}">
                                                @elseif ($payment_gateway->mode == 'test' && isset($payment_gateway->test_values['callback_url']))
                                                    <input type="hidden" name="callback"
                                                        value="{{ $payment_gateway->test_values['callback_url'] }}">
                                                @else
                                                    <input type="hidden" name="callback" value="">
                                                @endif

                                                <input type="hidden" name="external_redirect_link"
                                                    value="{{ url('/') . '/web-payment' }}">
                                                <label
                                                    class="d-flex align-items-center gap-2 mb-0 form-check py-2 cursor-pointer">
                                                    <input type="radio" id="{{ $payment_gateway->key_name }}"
                                                        name="online_payment" class="form-check-input custom-radio"
                                                        value="{{ $payment_gateway->key_name }}">
                                                    <img width="30"
                                                        src="{{ dynamicStorage(path: 'storage/app/public/payment_modules/gateway_image') }}/{{ $payment_gateway->additional_data && json_decode($payment_gateway->additional_data)->gateway_image != null ? json_decode($payment_gateway->additional_data)->gateway_image : '' }}"
                                                        alt="">
                                                    <span class="text-capitalize form-check-label">
                                                        @if ($payment_gateway->additional_data && json_decode($payment_gateway->additional_data)->gateway_title != null)
                                                            {{ json_decode($payment_gateway->additional_data)->gateway_title }}
                                                        @else
                                                            {{ str_replace('_', ' ', $payment_gateway->key_name) }}
                                                        @endif

                                                    </span>
                                                </label>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if (isset($offline_payment) && $offline_payment['status'] && count($offline_payment_methods) > 0)
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="bg-primary-light rounded p-4">
                                            <div
                                                class="d-flex justify-content-between align-items-center gap-2 position-relative">
                                                <span class="d-flex align-items-center gap-3">
                                                    <input type="radio" id="pay_offline" name="online_payment"
                                                        class="custom-radio" value="pay_offline">
                                                    <label for="pay_offline"
                                                        class="cursor-pointer d-flex align-items-center gap-2 mb-0 text-capitalize">{{ translate('pay_offline') }}</label>
                                                </span>

                                                <div data-toggle="tooltip"
                                                    title="{{ translate('for_offline_payment_options,_please_follow_the_steps_below') }}">
                                                    <i class="tio-info text-primary"></i>
                                                </div>
                                            </div>

                                            <div class="mt-4 pay_offline_card d-none">
                                                <div class="d-flex flex-wrap gap-3">
                                                    @foreach ($offline_payment_methods as $method)
                                                        <button type="button"
                                                            class="btn btn-light offline_payment_button text-capitalize"
                                                            id="{{ $method->id }}">{{ $method->method_name }}</button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            @include('web-views.partials._order-summary')
        </div>
    </div>

    @if (isset($offline_payment) && $offline_payment['status'])
        <div class="modal fade" id="selectPaymentMethod" tabindex="-1" aria-labelledby="selectPaymentMethodLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('offline-payment-checkout-complete') }}" method="post"
                            class="needs-validation">
                            @csrf
                            <div class="d-flex justify-content-center mb-4">
                                <img width="52"
                                    src="{{ theme_asset(path: 'public/assets/front-end/img/select-payment-method.png') }}"
                                    alt="">
                            </div>
                            <p class="fs-14 text-center">
                                {{ translate('pay_your_bill_using_any_of_the_payment_method_below_and_input_the_required_information_in_the_form') }}
                            </p>

                            <select class="form-control mx-xl-5 max-width-661" id="pay_offline_method" name="payment_by"
                                required>
                                <option value="" disabled>{{ translate('select_Payment_Method') }}</option>
                                @foreach ($offline_payment_methods as $method)
                                    <option value="{{ $method->id }}">{{ translate('payment_Method') }} :
                                        {{ $method->method_name }}</option>
                                @endforeach
                            </select>
                            <div class="" id="payment_method_field">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (auth('customer')->check() && $wallet_status == 1)
        <div class="modal fade" id="wallet_submit_button" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ translate('wallet_payment') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @php($customer_balance = auth('customer')->user()->wallet_balance)
                    @php($remain_balance = $customer_balance - $amount)
                    <form action="{{ route('checkout-complete-wallet') }}" method="get" class="needs-validation">
                        @csrf
                        <div class="modal-body">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label for="">{{ translate('your_current_balance') }}</label>
                                    <input class="form-control" type="text"
                                        value="{{ webCurrencyConverter(amount: $customer_balance ?? 0) }}" readonly>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label for="">{{ translate('order_amount') }}</label>
                                    <input class="form-control" type="text"
                                        value="{{ webCurrencyConverter(amount: $amount ?? 0) }}" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label for="">{{ translate('remaining_balance') }}</label>
                                    <input class="form-control" type="text"
                                        value="{{ webCurrencyConverter(amount: $remain_balance ?? 0) }}" readonly>
                                    @if ($remain_balance < 0)
                                        <label
                                            class="__color-crimson mt-1">{{ translate('you_do_not_have_sufficient_balance_for_pay_this_order!!') }}</label>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ translate('close') }}</button>
                            <button type="submit" class="btn btn--primary"
                                {{ $remain_balance > 0 ? '' : 'disabled' }}>{{ translate('submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <span id="route-action-checkout-function" data-route="checkout-payment"></span>
@endsection

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/payment.js') }}"></script>

    <script>
        $('.action-checkout-function').on('click', function() {
            let checked_button_id = $('input[type="radio"]:checked').attr('id');
            if (checked_button_id === 'cash_on_delivery_cod') {
                $('#cash_on_delivery_form_cod').submit();
            } else {
                $('#' + checked_button_id + '_form').submit();
            }
        })
    </script>
@endpush
