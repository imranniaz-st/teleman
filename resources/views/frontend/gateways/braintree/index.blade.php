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
    <title>{{ translate('PayPal Payment') }}</title>
    <!-- Favicon Icon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/gif">

    <link rel="stylesheet" href="{{ asset('subscription/css/bootstrap.css') }}" />    
    <link rel=stylesheet type=text/css href="{{ asset('css/app.css') }}">
    <link rel=stylesheet type=text/css href="{{ asset('css/overrides.css') }}">

</head>

<body class="saas_paypal_bg">
    <header class="main custom-stripe-header">
        <div class="container wide">
            <div class="content slim">
                <div class="set">
                    <div class="fill">
                        <a href="{{ route('frontend') }}" class="pseudoshop">
                            <img src="{{ logo() }}" alt="{{ appName() }}" class="w-25"/>
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

            <form method="post" id="payment-form" action="{{ route('braintree.checkout') }}">
                @csrf
                <section>
                    <label for="amount">
                        <span class="input-label">{{ translate('Amount') }}</span>
                        <div class="input-wrapper amount-wrapper">
                            <input id="amount" name="amount" type="tel" min="1" placeholder="Amount"
                                value="{{ getPayableAmountFromInvoice(Session::get('invoice'), 'USD') }}" disabled>
                        </div>
                    </label>

                    <div class="bt-drop-in-wrapper">
                        <div id="bt-dropin"></div>
                    </div>
                </section>

                <input id="nonce" name="payment_method_nonce" type="hidden" />
                <button class="button" type="submit"><span>{{ translate('Make Payment') }}</span></button>
            </form>
        </div>
    </div>

    <div class="saas_pos_copyright custom-saas_pos_copyright">
        <span> <h5 class="braintree">{{ translate('Powered By Braintree') }}</h5></span>
    </div>
    <script src="https://js.braintreegateway.com/web/dropin/1.32.0/js/dropin.min.js"></script>
    <script>
        "use strict";
        var form = document.querySelector('#payment-form');
        var client_token = "{{ $token }}";

        braintree.dropin.create({
            authorization: client_token,
            selector: '#bt-dropin',
            paypal: {
                flow: 'vault'
            }
        }, function (createErr, instance) {
            if (createErr) {
                console.log('Create Error', createErr);
                return;
            }
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                instance.requestPaymentMethod(function (err, payload) {
                    if (err) {
                        console.log('Request Payment Method Error', err);
                        return;
                    }

                    // Add the nonce to the form and submit
                    document.querySelector('#nonce').value = payload.nonce;
                    form.submit();
                });
            });
        });

    </script>
    <script src="{{ asset('js/demo.js') }}"></script>
</body>

</html>
