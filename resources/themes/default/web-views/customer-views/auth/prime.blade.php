@extends('layouts.front-end.app')

@section('title',  translate('register'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/css/intlTelInput.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="container py-4 __inline-7 text-align-direction">
        <div class="login-card" style="width:100%">
            <div class="mx-auto __max-w-760">
                <h2 class="text-center h4 mb-4 font-bold text-capitalize fs-18-mobile">{{ 'prime memebership'}}</h2>
               
                    <div class="row">
                 
                      
                        @if ($web_config['ref_earning_status'])
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label font-semibold">{{ translate('refer_code') }} <small class="text-muted">({{ translate('optional') }})</small></label>
                                <input type="text" id="referral_code" class="form-control"
                                name="referral_code" placeholder="{{ translate('use_referral_code') }}">
                            </div>
                        </div>
                        @endif

                    </div>
                    <div class="col-12">
                        <div class="row g-3">
                        
                          



        <div class="col-sm-6">
    <div class="rtl">
        <label class="custom-control custom-checkbox m-0 d-flex" for="prime-membership">
            <input type="checkbox" checked class="custom-control-input" name="primemembership" id="prime-membership">
            <span class="custom-control-label">
                <span>&nbsp;&nbsp;{{ translate('prime_membership') }}</span> 
            </span>
        </label>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    var checkbox = document.getElementById('prime-membership');
    var element = document.getElementById('subscription_box');
 element.classList.add('show');

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            element.classList.remove('hide');
            element.classList.add('show');
        } else {
            element.classList.remove('show');
            element.classList.add('hide');
        }
    });



  //const totalButtons = document.querySelectorAll('[class^="testdata--"]').length;

   for (let i = 0; i < 4; i++) {
        let button = document.querySelector(`.testdata--${i}`);
        
        if (button) {
            button.addEventListener('click', function() {
                // Get the form data
                var phone = document.getElementById('phone') ? document.getElementById('phone').value : '';

var user_id = document.getElementById('user_id') ? document.getElementById('user_id').value : '';

                var name = document.getElementById('f_name') ? document.getElementById('f_name').value : '';
                var email = document.getElementById('email') ? document.getElementById('email').value : '';
                
                var price = document.querySelector(`.price-${i}`) ? document.querySelector(`.price-${i}`).value : '';

                var type = document.querySelector(`.type-${i}`) ? document.querySelector(`.type-${i}`).value : '';

                // Initialize a flag for form validity
                let isValid = true;

              

                // Check if phone is empty
                if (phone === '') {
                    alert("Phone field is required.");
                    isValid = false;
                }
                
                // Check if name is empty
                if (name === '') {
                    alert("Name field is required.");
                    isValid = false;
                }
                
                // Check if email is empty
                if (email === '') {
                    alert("Email field is required.");
                    isValid = false;
                }
                
                // If form is valid, send the data
                if (isValid) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Prepare the form data
                    const formData = new URLSearchParams();
                    formData.append('phone', phone);
                    formData.append('name', name);
                    formData.append('email', email);
                    formData.append('price', price);
                    formData.append('duration', type);
                    formData.append('user_id', user_id);
                    formData.append('_token', csrfToken);

                    fetch('{{ route("customer.auth.subscribe") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {

                        if(data.error)
                        {
                            // alert("hello");
                            alert(data.error);
                   alert("Mobile number length should be 10 digits");
                   window.location.href= window.location.origin;
                        }
                        else
                        {
// alert("hello1");
                        window.location.href=data.redirect;
                     }

                    })
                    .catch(error => {
             // alert("hello2");
                        console.error('Error:', error);
                        alert('An error occurred.',error);
                        window.location.href=window.location.origin;
                    });
                }
            });
        }
    }

});
    

</script>

<style>
    .hide {
    display: none;
}
.show {
    display: block;
}
    .title {
    text-align: center;
    margin: 10px auto 10px auto;
    z-index: 1;
    margin-top: 15px;
}

h2 {
     margin-top: 20px;
}



small {
    font-size: 12px;
    color: gray;
}

.small-colored {
    color: #47cf73;
}



.container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    z-index: 1;
}

.offers {
    position: relative;
    text-align: center;
    background: #fff;
    padding: 1%;
    margin: 10px;
    width: 30%;
    height: auto;
    top: 0;
    border: 1px solid #eaeaea;
    z-index: 1;
    -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out; 
}

.offers:hover {
    position: relative;
    top: -20px;
    box-shadow: 0px 14px 6px 0px #0000004d;
}

.offers:nth-child(2) {
    border-top: 2px solid #47cf73;
    box-shadow: 0 0 10px 0px #0000001c;
}

.offers:nth-child(2) h3{
    margin-top: 20px;
}

.offers:nth-child(2):hover {
    box-shadow: 0px 14px 6px 0px #0000004d;
}

.subscribe {
  
    background: #5c5f61;
    color:white;
    margin-top:15px;
    margin-bottom:15px;
  
}

.subscribe:hover {
  
    background: #5c5f61;
    color:white;
  
}

.Subscription_box{width:100%; border:1px solid #0000004d;background: transparent;box-shadow: 0px 14px 6px 0px #0000004d;}

@media only screen and (max-width: 768px) {

.offers
{
    width:100%;
}

}

</style>
<div class="Subscription_box hide" id="subscription_box">
<h2 class="text-center h4 mb-4 font-bold text-capitalize fs-18-mobile">{{ translate('subscriptions') }} {{ translate('plans') }}</h1>
   



    <div class="container">


        <form class="needs-validation_" style="width:100%" id="customer-subscribe-form" action="{{ route('customer.auth.subscribe')}}"
                        method="post">
                    @csrf
        <div class="container">

  <!-- Hidden input to store user ID -->
        <input type="hidden" id="user_id" name="user_id" value="{{ $userdata->id ??'' }}">

        <!-- Other form fields -->
        <input type="hidden" name="name" id="f_name" value="{{ $userdata->f_name ??'' }}"/>
        <input type="hidden" name="email" id="email" value="{{ $email ??'jondeeka@gmail.com' }}" />
          <input type="hidden" name="phone" id="phone" value="{{ $userdata->phone??'' }}" />

            @foreach($plandata as $index=> $pland)
                <div class="offers">
                    <h2 class="text-center h4 mb-4 font-bold text-capitalize fs-18-mobile">
                        {{ translate(strtolower($pland->name)) }}
                    </h2>
                    <input type="hidden" value={{ $pland->price }} class="price-{{ $index }}" />
 
 <input type="hidden" value={{ $pland->type }} class="type-{{ $index }}" />



                    <h3 class="price">SAR {{ $pland->price }}</h3>
                    
                    <p>
                        @if($pland->type == 1)
                        {{translate('monthly') }}

                        @elseif($pland->type == 2)
                            6 {{ translate('months') }}
                        @elseif($pland->type == 3)
                            
                            {{ translate('annually') }}
                        @endif
                    </p>
                    <button class="subscribe testdata--{{ $index }} w-100 btn"  type="button">{{ translate('subscribe') }}</button>
                </div>
            @endforeach
        </div>
    </form>
  </div>


                            
                        </div>
                    </div>
                    <div class="web-direction">
                       

                        <div class="text-black-50 mt-3 text-center">
                            <small>
                                {{  translate('Already_have_account ') }}?
                                <a class="text-primary text-underline" href="{{ route('customer.auth.login') }}">
                                    {{ translate('sign_in') }}
                                </a>
                            </small>
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script src="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/country-picker-init.js') }}"></script>
@endpush
