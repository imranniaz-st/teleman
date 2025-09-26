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
    <title>{{ translate('Stripe Payment') }}</title>
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

            <form
                role="form"
                action="{{ route('stripe.post') }}"
                method="post"
                class="require-validation"
                data-cc-on-file="false"
                data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                id="payment-form">
                @csrf

                <input type="hidden" name="amount" value="{{ getPayableAmountFromInvoice(Session::get('invoice'), 'USD') }}">
                <input type="hidden" name="invoice" value="{{ Session::get('invoice') }}">
                @if (Session::has('renew_subscription_details'))
                    <input type="hidden" name="package_name" value="{{ PackageDetails(Session::get('renew_subscription_details')['package_id'])->name }}">
                @else
                    <input type="hidden" name="package_name" value="{{ PackageDetails(Session::get('subscription_details')['package_id'])->name }}">
                @endif

                
                <section>
                    <label for="amount">
                        <span class="input-label">{{ translate('Amount') }}</span>
                        <div class="input-wrapper amount-wrapper">
                            <input id="amount" name="amount" type="tel" min="1" placeholder="Amount"
                                value="{{ getPayableAmountFromInvoice(Session::get('invoice'), 'USD') }}" disabled>
                        </div>
                    </label>

                    <div class="bt-drop-in-wrapper">

                            <div class='form-row row'>
                                <div class='col-md-12 form-group required'>
                                    <label class='control-label'>{{ translate('Name on Card') }}</label> <input
                                        class='form-control w-100' type='text' placeholder="{{ translate('Name on Card') }}">
                                </div>
                            </div>

                            <div class='form-row row mt-2'>
                                <div class='col-md-12 form-group required'>
                                    <label class='control-label'>{{ translate('Card Number') }}</label> 
                                    <input
                                        autocomplete='off' class='form-control card-number' size='20'
                                        placeholder="{{ translate('Card Number') }}"
                                        type='text'
                                        class="w-100">
                                </div>
                            </div>

                            <div class='form-row row mt-2'>
                                <div class='col-xs-12 col-md-4 form-group cvc required'>
                                    <label class='control-label'>{{ translate('CVC') }}</label> <input autocomplete='off'
                                        class='form-control card-cvc' placeholder='ex. 311' size='4'
                                        placeholder="{{ translate('CVC') }}"
                                        type='text'>
                                </div>
                                <div class='col-xs-12 col-md-4 form-group expiration required'>
                                    <label class='control-label'>{{ translate('Expiration Month') }}</label> <input
                                        class='form-control card-expiry-month' placeholder='MM' size='2'
                                        placeholder="{{ translate('Expiration Month') }}"
                                        type='text'>
                                </div>
                                <div class='col-xs-12 col-md-4 form-group expiration required'>
                                    <label class='control-label'>{{ translate('Expiration Year') }}</label> <input
                                        class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                        ceholder="{{ translate('Expiration Year') }}"
                                        type='text'>
                                </div>
                            </div>
                            
                    </div>
                </section>

                <button class="button w-100" type="submit"><span>{{ translate('Make Payment') }}</span></button>
            </form>
        </div>
    </div>

    <div class="saas_pos_copyright custom-saas_pos_copyright">
        <span> <h5 class="braintree">{{ translate('Powered By Stripe') }}</h5></span>
    </div>
  
    <script src="{{ asset('subscription/js/jquery-3.6.0.js') }}"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
   <script type="text/javascript">
   "use strict";
      $(function() {
    var $form = $(".require-validation");
    $('form.require-validation').bind('submit', function(e) {
        var $form = $(".require-validation"),
            inputSelector = ['input[type=email]', 'input[type=password]',
                'input[type=text]', 'input[type=file]',
                'textarea'
            ].join(', '),
            $inputs = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid = true;
        $errorMessage.addClass('hide');
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
        });
        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
    });
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
});
   </script>
</body>

</html>
