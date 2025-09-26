<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required Meta Tags -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="author" content="{{ env('AUTHOR') }}">
    <meta name="version" content="{{ env('VERSION') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ translate('Razorpay Payment') }}</title>
    <!-- Favicon Icon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/gif">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('subscription/css/bootstrap.css') }}" />    
    <link rel=stylesheet type=text/css href="{{ asset('css/app.css') }}">
    <link rel=stylesheet type=text/css href="{{ asset('css/overrides.css') }}">

</head>

<body class="saas_paypal_bg">
    <header class="main custom-stripe-header">
        <div class="container">
            <div class="pt-2 pb-2">
                <div class="set">
                    <div class="fill">
                        <a href="{{ route('frontend') }}">
                            <img src="{{ logo() }}" alt="{{ appName() }}" class="w-25" />
                        </a>
                    </div>

                    <div class="fit">
                        <a class="braintree" href="{{ route('frontend') }}">{{ orgName() }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="notice-wrapper">
            
            <?php if(isset($_SESSION["errors"])) : ?>
            <div class="show notice error notice-error">
                <span class="notice-message">
                    <?php
                        echo($_SESSION["errors"]);
                        unset($_SESSION["errors"]);
                    ?>
                    <span>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <div class="wrapper saas_paypal_wrapper custom-saas_paypal_wrapper">
        <div class="checkout container">

            <header>
                <h3>{{ translate('Hi') }}, <strong>{{ getUserInfoFromInvoice(Session::get('invoice'))->name }}</strong></h3>
                <h3>{{ translate('Invoice No.') }} #{{ Session::get('invoice') }} </h3>
                <h3>
                    {{ translate('You are making payment for') }}
                    <strong>
                        @if (Session::has('renew_subscription_details'))
                        {{ PackageDetails(Session::get('renew_subscription_details')['package_id'])->name }} 
                        @else
                        {{ PackageDetails(Session::get('subscription_details')['package_id'])->name }} 
                    @endif
                    {{ translate('Plan') }}
                    </strong>
                </h3>
            </header>

            <section>
                    <label for="amount">
                        <span class="input-label">{{ translate('Amount') }}</span>
                        <div class="input-wrapper">
                            <input id="amount" name="amount" type="tel" min="1" placeholder="Amount"
                                value="₹{{ getPayableAmountFromInvoice(Session::get('invoice'), 'INR') }}" disabled>
                        </div>
                    </label>
                </section>


            <button id="rzp-button1" hidden>Pay</button>

            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            <script>
                var options = {
                    "key": "{{$response['razorpayId']}}", // Razorpay ID
                    "amount": "{{$response['amount']}}", // Amount
                    "currency": "{{$response['currency']}}",
                    "name": "{{$response['name']}}",
                    "description": "{{$response['description']}}",
                    "image": "{{ asset(application('site_favicon')) }}", // replace this link with actual logo
                    "order_id": "{{$response['orderId']}}", //Created Order id in first method
                    "handler": function (response){
                        document.getElementById('rzp_paymentid').value = response.razorpay_payment_id;
                        document.getElementById('rzp_orderid').value = response.razorpay_order_id;
                        document.getElementById('rzp_signature').value = response.razorpay_signature;
                        document.getElementById('rzp-paymentresponse').click();
                    },
                    "prefill": {
                        "name": "{{$response['name']}}",
                        "email": "{{$response['email']}}",
                        "contact": "{{$response['contactNumber']}}"
                    },
                    "notes": {
                        "address": "{{$response['address']}}"
                    },
                    "theme": {
                        "color": "#F37254"
                    }
                };
                var rpay = new Razorpay(options);
                window.onload = function(){
                    document.getElementById('rzp-button1').click();
                };

                document.getElementById('rzp-button1').onclick = function(e){
                    rpay.open();
                    e.preventDefault();
                }
            </script>
            <form action="{{route('razorpay.make-payment')}}" method="POST" hidden>
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="text" class="form-control" id="rzp_paymentid"  name="rzp_paymentid">
                    <input type="text" class="form-control" id="rzp_orderid" name="rzp_orderid">
                    <input type="text" class="form-control" id="rzp_signature" name="rzp_signature">
                <button type="submit" id="rzp-paymentresponse" class="btn btn-primary">Submit</button>
            </form>
            
        </div>
    </div>

    <div class="saas_pos_copyright custom-saas_pos_copyright">
        <span> <h5 class="braintree">{{ translate('Powered By Razorpay') }}</h5></span>
    </div>
  

    
    
</body>

</html>
