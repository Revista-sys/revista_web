@extends('layouts.front-end.app')

@section('title', translate('choose_Payment_Method'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/payment.css') }}">
    <script src="https://demo.myfatoorah.com/applepay/v3/applepay.js"></script>
    <script src="https://demo.myfatoorah.com/stcPay/v1/stcpay.js"></script>
    <script src="https://demo.myfatoorah.com/payment/v1/session.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script> --}}
    {{-- <script src="https://js.stripe.com/v3/"></script> --}}
@endpush

@section('content')
    <div id="unified-session"></div>
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

                                            <div id="mf-apple-pay"></div>
                                            <div id="mf-stc-pay"></div>
                                            <div id="mf-card-element" style="width: 500px">
                                                <div id="card-element"></div>
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

    <!-- Add MyFatoorah Configuration Script -->
    <script>
        // -- myfatoorah --
        var myfatoorahAP = window.myFatoorahAP;
        var myfatoorahSTC = window.myFatoorahStc;
        var myfatoorah = window.myfatoorah;

        // -- apple pay --
        // apple pay config
        var applePayConfig = {
            sessionId: "{{ $session->SessionId }}",
            countryCode: "{{ $session->CountryCode }}",
            currencyCode: "SAR",
            amount: "{{ $amount }}",
            cardViewId: "mf-apple-pay",
            callback: applePayCallback,
            sessionStarted: sessionStarted,
            sessionCanceled: sessionCanceled,
            style: {
                button: {
                    useCustomButton: true,
                    textContent: "Pay",
                    fontSize: "18px",
                    height: "40px",
                    borderRadius: "8px",
                    width: "70%",
                }
            }
        }

        // init apple pay
        myfatoorahAP.init(applePayConfig);

        // apple pay callback
        function applePayCallback(response) {
            try {
                console.log("response >> " + JSON.stringify(response));
            } catch (error) {
                console.log("error >> " + JSON.stringify(error));
            }
        }

        // -- stc pay --
        var stcPayConfig = {
            sessionId: "{{ $session->SessionId }}", //Here you add the "SessionId" you receive from InitiateSession Endpoint.
            countryCode: "{{ $session->CountryCode }}", //Here, add your "CountryCode" you receive from InitiateSession Endpoint.
            amount: "{{ $amount }}",
            // mobileNumber: "0557877988", 
            containerId: "mf-stc-pay",
            callback: stcPayCallback,
            style: {
                fontSize: "25px",
                borderRadius: "8px",
                button: {
                    height: '40',
                }
                // frameWidth: "70%,
            }
        }

        // init stc pay
        myfatoorahSTC.init(stcPayConfig);

        // stc pay callback
        function stcPayCallback(response) {
            console.log("response >> " + JSON.stringify(response));
            try {
                console.log("response >> " + JSON.stringify(response));

                if (response) {
                    $.ajax({
                        url: '{{ route('execute-payment') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify({
                            sessionId: response.sessionId,
                            invoiceValue: "{{ $amount }}",
                        }),
                        success: function(serverResponse) {
                            console.log(serverResponse);
                            // Open popup window
                            const popup = window.open(serverResponse.redirect_url, 'PaymentWindow',
                                'width=800,height=600,left=200,top=100');

                            // Monitor popup location changes
                            const timer = setInterval(function() {
                                try {
                                    if (popup.closed) {
                                        clearInterval(timer);
                                        window.location.href =
                                            'http://localhost/products?id=13&data_from=category&page=1';
                                    }

                                    // Check current URL
                                    const currentUrl = popup.location.href;
                                    console.log('Current popup URL:', currentUrl);

                                    // Optional: Handle specific URLs
                                    if (currentUrl.includes('success') || currentUrl.includes(
                                            'completed')) {

                                        console.log(currentUrl)

                                        // Handle success
                                        clearInterval(timer);
                                        popup.close();
                                        window.location.reload();
                                    }
                                } catch (e) {
                                    // Cross-origin errors will be caught here
                                    console.log('Navigation in progress...');
                                }
                            }, 500);
                        },
                        error: function(xhr) {
                            console.error('errormessage', xhr.error());
                        }
                    })
                }

            } catch (error) {
                console.log("error >> " + JSON.stringify(error));
            }
        }

        // -- card pay --
        // card payment config
        var cardConfig = {
            sessionId: "{{ $session->SessionId }}",
            countryCode: "{{ $session->CountryCode }}",
            currencyCode: "SAR",
            amount: "{{ $amount }}",
            callback: cardPayment,
            paymentOptions: ["Card"], //"GooglePay", "ApplePay", "Card"
            containerId: "mf-card-element",
            cardViewId: "card-element",
            supportedNetworks: ["v", "m", "md"],
            language: "en",

            settings: {
                card: {
                    onCardChanged: function(card) {
                        console.log("card >> " + JSON.stringify(card));
                    },
                    style: {
                        hideNetworkIcons: false,
                        cardHeight: "200px",
                        tokenHeight: "230px",
                        width: "500px",
                        input: {
                            color: "black",
                            fontSize: "15px",
                            inputHeight: "45px",
                            backgroundColor: "green",
                            borderRadius: "30px",
                            outerRadius: "10px",
                            placeHolder: {
                                holderName: "Name On Card",
                                cardNumber: "Number",
                                expiryDate: "MM/YY",
                                securityCode: "CVV",
                            }
                        },
                        text: {
                            saveCard: "Save card info for future payments",
                            addCard: "Use another Card!",
                            deleteAlert: {
                                title: "Delete",
                                message: "Are you sure?",
                                confirm: "YES",
                                cancel: "NO"
                            }
                        },
                        error: {
                            borderColor: "red",
                            borderRadius: "3px",
                        },
                        button: {
                            useCustomButton: false,
                            //onButtonClicked: submit,
                            textContent: "Pay",
                            fontSize: "18px",
                            fontFamily: "Times",
                            height: "40px",
                            borderRadius: "8px",
                            width: "70%",
                            margin: "0 auto",
                            cursor: "pointer"
                        },
                        separator: {
                            useCustomSeparator: false,
                            fontSize: "20px",
                            fontFamily: "sans-serif",
                            textSpacing: "2px",
                            lineStyle: "dashed",
                        }
                    }
                }
            }
        };

        // init card pay
        myfatoorah.init(cardConfig);



        // card payment callback
        async function cardPayment(response) {
            //Pass session id to your backend here
            try {
                console.log("response >> " + JSON.stringify(response));

                if (response) {
                    $.ajax({
                        url: '{{ route('execute-payment') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify({
                            sessionId: response.sessionId,
                            invoiceValue: "{{ $amount }}"
                        }),
                        success: function(serverResponse) {
                            console.log(serverResponse);
                            // Open popup window
                            const popup = window.open(serverResponse.redirect_url, 'PaymentWindow',
                                'width=800,height=600,left=200,top=100');

                            // Monitor popup location changes
                            const timer = setInterval(function() {
                                try {
                                    if (popup.closed) {
                                        clearInterval(timer);
                                        window.location.href =
                                            'http://localhost/products?id=13&data_from=category&page=1';
                                    }

                                    // Check current URL
                                    const currentUrl = popup.location.href;
                                    console.log('Current popup URL:', currentUrl);

                                    // Optional: Handle specific URLs
                                    if (currentUrl.includes('success') || currentUrl.includes(
                                            'completed')) {

                                        console.log(currentUrl)

                                        // Handle success
                                        clearInterval(timer);
                                        popup.close();
                                        window.location.reload();
                                    }
                                } catch (e) {
                                    // Cross-origin errors will be caught here
                                    console.log('Navigation in progress...');
                                }
                            }, 500);
                        },
                        error: function(xhr) {
                            console.error('errormessage', xhr.error());
                        }
                    })
                }

            } catch (error) {
                console.log("error >> " + JSON.stringify(error));
            }

            // window.location = 'embedded-payment-sample-code-call-ExecutePayment.php?sessionId=' +
            //     "{{ $session->SessionId }}";
        }

        // message status
        function messageStatus(message) {
            console.log("message >> " + message);
        }

        // session canceled
        function sessionCanceled() {
            console.log("Failed");
        }

        // session started
        function sessionStarted() {
            console.log("Start");
        }
    </script>
@endpush
