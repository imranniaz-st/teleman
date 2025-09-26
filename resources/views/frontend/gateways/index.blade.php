@extends('frontend.titania.layouts.master')

@section('title')
    {{ translate('Make Payment') }}
@endsection

@section('css')
    
@endsection

@section('content')
    <div id="checkout-flow" class="section">

      <!--Nav-->
        @includeWhen(true, 'frontend.titania.components.nav')
        <!--Nav::END-->

        <div class="container">
            <div class="cart-view">

                <div class="payment-process">
                    <!--Step 1-->
                    <div id="checkout-flow-step-0" class="payment-process-block">
                        <div class="payment-process-left">
                            <div class="order-summary">
                                <div class="flex-table">
                                    <!--Table header-->
                                    <div class="flex-table-header">
                                        <span class="product h6">{{ translate('Package') }}</span>
                                        <span class="price h6">{{ translate('Price') }}</span>
                                        <span class="discount h6">{{ translate('Discount') }}</span>
                                        <span class="total h6">{{ translate('Total') }}</span>
                                    </div>
                                    <!--Table item-->
                                    <div class="flex-table-item" data-product-id="107">
                                        <div class="product">
                                            <span class="product-name h5">{{ PackageDetails($payment->package_id)->name }}</span>
                                        </div>
                                        <div class="discount">
                                            <span class="has-price h5">{{ price($payment->amount) }}</span>
                                        </div>
                                        <div class="discount">
                                            <span class="has-price h5">{{ price(0) }}</span>
                                        </div>
                                        <div class="discount">
                                            <span class="has-price h5">{{ price($payment->amount) }}</span>
                                        </div>
                                    </div>

                                    <div class="flex-table-header mt-4">
                                      <span class="price h6">{{ translate('Credits') }}</span>
                                        <span class="discount h6">{{ translate('Validity') }}</span>
                                    </div>

                                    <div class="flex-table-item" data-product-id="108">
                                        <div class="discount">
                                            <span class="has-price h5">{{ PackageDetails($payment->package_id)->credit }}</span>
                                        </div>
                                        <div class="discount">
                                            <span class="has-price h5">{{ PackageDetails($payment->package_id)->range }} {{ Str::plural(PackageDetails($payment->package_id)->range_type, PackageDetails($payment->package_id)->range) }}</span>
                                        </div>
                                    </div>

                                    <div class="flex-table-header mt-4">
                                      <span class="product h6">{{ translate('Features') }}</span>
                                    </div>

                                    <div class="tags">
                                        @forelse (json_decode(PackageDetails($payment->package_id)->feature_id, true) as $feature)
                                            @if (featureName($feature))
                                                <span class="tag squared is-outlined title disabled">{{ Str::upper(featureName($feature)) }}</span>
                                            @endif
                                        @empty
                                                  
                                        @endforelse
                                    </div>
                              
                                </div>
                            </div>
                        </div>
                        <div class="payment-process-right">
                            <div class="action-box">
                                <div class="intructions-block">
                                    <h2>{{ translate('Verify your order') }}</h2>
                                    <p>
                                        {{ translate('Please verify your order details and click the button below to complete your payment.') }}
                                    </p>
                                    <div class="field">
                                        <div class="control">
                                            <label class="checkbox-wrap is-medium">
                                                <input type="checkbox" id="termsCheckbox" class="d-checkbox" checked required/>
                                                <span></span>
                                                {{ translate('I agree to the Terms and Conditions') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Step 2-->
                    <div id="checkout-flow-step-1" class="payment-process-block is-hidden mt-16rem">
                        <div class="payment-process-left">
                            <div class="payment-methods-grid">
                                <div class="columns is-multiline"> 
                                    <!--Payment Method-->
                                    @if (teleman_config('braintree') == "YES")
                                    <div class="column is-6">
                                        <div class="method-card">
                                            <input id="paypal" type="radio" name="payment_methods" data-value-id="paypal" onclick="SubmitBraintree()"/>
                                            <div class="method-card-inner">
                                                <div class="icon-container">
                                                    <img src="{{ asset('frontend/titania/assets/img/graphics/icons/checkout/paypal.svg') }}" alt="{{ translate('Paypal') }}" />
                                                    <div class="indicator gelatine">
                                                        <i data-feather="check"></i>
                                                    </div>
                                                </div>
                                                <div class="meta">
                                                    <h3>{{ translate('Paypal') }}</h3>
                                                    <p>
                                                        {{ translate('Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="{{ route('braintree.index') }}" method="post" id="braintree_form"> @csrf </form>

                                    </div>
                                    @endif
                                    <!--Payment Method-->
                                    @if (teleman_config('stripe') == "YES")
                                    <div class="column is-6">
                                        <div class="method-card">
                                            <input id="stripe" type="radio" name="payment_methods" data-value-id="stripe" onclick="SubmitStripe()"/>
                                            <div class="method-card-inner">
                                                <div class="icon-container">
                                                    <img src="{{ asset('frontend/titania/assets/img/graphics/icons/checkout/stripe.svg') }}" alt="{{ translate('Stripe') }}" />
                                                    <div class="indicator gelatine">
                                                        <i data-feather="check"></i>
                                                    </div>
                                                </div>
                                                <div class="meta">
                                                    <h3>{{ translate('Stripe') }}</h3>
                                                    <p>
                                                        {{ translate('Pay via Stripe; you can pay with your credit card if you don\'t have a Stripe account.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" value="{{ route('stripe.hostpage') }}" id="stripe_form">
                                    </div>
                                    @endif
                                    <!--Payment Method-->
                                    @if (teleman_config('ssl_commerz') == "YES")
                                    <div class="column is-6">
                                        <div class="method-card">
                                            <input id="ssl" type="radio" name="payment_methods" data-value-id="credit-card" onclick="SubmitSSL()"/>
                                            <div class="method-card-inner">
                                                <div class="icon-container">
                                                    <img src="{{ asset('ssl.png') }}" alt="" />
                                                    <div class="indicator gelatine">
                                                        <i data-feather="check"></i>
                                                    </div>
                                                </div>
                                                <div class="meta">
                                                    <h3>{{ translate('SSL COMMERZ') }}</h3>
                                                    <p>
                                                        {{ translate('Pay via SSL COMMERZ; you can pay with your credit card if you don\'t have a SSL COMMERZ account.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>


                                        <form action="{{ route('ssl.pay') }}" method="post" id="ssl_form"> @csrf </form>

                                    </div>
                                    @endif

                                    {{-- payment method --}}
                                    @if (teleman_config('flutterwave') == "YES")
                                    <div class="column is-6">
                                        <div class="method-card">
                                            <input id="ssl" type="radio" name="payment_methods" data-value-id="credit-card" onclick="SubmitFLUTTERWAVE()"/>
                                            <div class="method-card-inner">
                                                <div class="icon-container">
                                                    <img src="{{ asset('payment_gatways/flw_icon.png') }}" class="rounded" alt="flutterwave" />
                                                    <div class="indicator gelatine">
                                                        <i data-feather="check"></i>
                                                    </div>
                                                </div>
                                                <div class="meta">
                                                    <h3>{{ translate('FLUTTERWAVE') }}</h3>
                                                    <p>
                                                        {{ translate('Pay via FLUTTERWAVE; you can pay with your credit card if you don\'t have a FLUTTERWAVE account.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>


                                        <form action="{{ route('rave.pay') }}" method="post" id="flutterwave_form"> @csrf </form>

                                    </div>
                                    @endif

                                    {{-- payment method --}}

                                    @if (teleman_config('paystack') == "YES")
                                    <div class="column is-6">
                                        <div class="method-card">
                                            <input id="ssl" type="radio" name="payment_methods" data-value-id="credit-card" onclick="SubmitPAYSTACK()"/>
                                            <div class="method-card-inner">
                                                <div class="icon-container">
                                                    <img src="{{ asset('payment_gatways/paystack.png') }}" class="rounded" alt="paystack" />
                                                    <div class="indicator gelatine">
                                                        <i data-feather="check"></i>
                                                    </div>
                                                </div>
                                                <div class="meta">
                                                    <h3>{{ translate('PAYSTACK') }}</h3>
                                                    <p>
                                                        {{ translate('Pay via Paystack; you can pay with your credit card if you don\'t have a Paystack account.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="{{ route('paystack.pay') }}" method="post" id="paystack_form" accept-charset="UTF-8" role="form">
                                             @csrf 

                                             @auth
                                             <input type="hidden" name="orderID" value="{{ session()->get('renew_subscription_details')->invoice }}">
                                            <input type="hidden" name="email" value="{{ session()->get('renew_subscription_details')->domain }}"> 
                                             
                                             @else
                                             <input type="hidden" name="orderID" value="{{ session()->get('subscription_details')->invoice }}">
                                            <input type="hidden" name="email" value="{{ session()->get('subscription_details')->domain }}"> 
                                                 
                                             @endauth

                                             {{-- check Paystack is well configured --}}
                                             @php
                                                 try { // check if Paystack is well configured
                                                     if (check_paystack_keys()) { // check if paystack keys are set
                                                        $genTranxRef = Paystack::genTranxRef(); // generate a transaction reference
                                                     }else { // if paystack keys are not set
                                                        $genTranxRef = null; // generate a transaction reference
                                                     }
                                                 } catch (\Exception $e) { // if paystack keys are not set
                                                        $genTranxRef = null; // generate a transaction reference
                                                 }
                                             @endphp
                                             {{-- check Paystack is well configured::ends --}}

                                            <input type="hidden" name="amount" value="{{ convertCurrency(teleman_config('paystack_merchant_currency'), onlyPrice($payment->amount)) }}">
                                            <input type="hidden" name="currency" value="{{ teleman_config('paystack_merchant_currency') }}">
                                            <input type="hidden" name="reference" value="{{ $genTranxRef }}">
                                        </form>

                                    </div>
                                    @endif

                                    {{-- payment method --}}
                                    @if (teleman_config('instamojo') == "YES")
                                    <div class="column is-6">
                                        <div class="method-card">
                                            <input id="instamojo" type="radio" name="payment_methods" data-value-id="credit-card" onclick="SubmitINSTAMOJO()"/>
                                            <div class="method-card-inner">
                                                <div class="icon-container">
                                                    <img src="{{ asset('payment_gatways/instamojo.png') }}" class="rounded" alt="instamojo" />
                                                    <div class="indicator gelatine">
                                                        <i data-feather="check"></i>
                                                    </div>
                                                </div>
                                                <div class="meta">
                                                    <h3>{{ translate('INSTAMOJO') }}</h3>
                                                    <p>
                                                        {{ translate('Pay via Instamojo; you can pay with your credit card if you don\'t have a Instamojo account.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="{{ route('instamojo.pay') }}" method="post" id="instamojo_form" accept-charset="UTF-8" role="form">
                                             @csrf 
                                        </form>

                                    </div>
                                    @endif

                                    {{-- payment method --}}
                                    @if (teleman_config('razorpay') == "YES")
                                    <div class="column is-6">
                                        <div class="method-card">
                                            <input id="stripe" type="radio" name="payment_methods" data-value-id="stripe" onclick="SubmitRAZORPAY()"/>
                                            <div class="method-card-inner">
                                                <div class="icon-container">
                                                    <img src="{{ asset('payment_gatways/razorpay.png') }}" alt="{{ translate('Razorpay') }}" />
                                                    <div class="indicator gelatine">
                                                        <i data-feather="check"></i>
                                                    </div>
                                                </div>
                                                <div class="meta">
                                                    <h3>{{ translate('Razorpay') }}</h3>
                                                    <p>
                                                        {{ translate('Pay via Razorpay; you can pay with your credit card if you don\'t have a Razorpay account.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" value="{{ route('razorpay.hostpage') }}" id="razorpay_form">
                                    </div>
                                    @endif

                                    {{-- payment method --}}
                                    @if (teleman_config('squad') == "YES")
                                    <div class="column is-6">
                                        <div class="method-card">
                                            <input id="squad" type="radio" name="payment_methods" data-value-id="squad" onclick="SubmitSQUAD()"/>
                                            <input type="hidden" id="email-address" value="{{ Auth::user()->email }}">
                                            <input type="hidden" id="amount" value="{{ convertCurrency(teleman_config('squad_merchant_currency'), onlyPrice($payment->amount)) }}">
                                            <input type="hidden" id="squad_merchant_currency" value="{{ teleman_config('squad_merchant_currency') }}">
                                            <input type="hidden" id="sandbox_pk" value="{{ teleman_config('squad_public_key') }}">

                                            <input type="hidden" id="squad_success_url" value="{{ route('squad.success') }}">
                                            <input type="hidden" id="squad_cancel_url" value="{{ route('squad.cancel') }}">

                                            <div class="method-card-inner">
                                                <div class="icon-container">
                                                    <img src="{{ asset('payment_gatways/squad.png') }}" alt="{{ translate('Squad') }}" />
                                                    <div class="indicator gelatine">
                                                        <i data-feather="check"></i>
                                                    </div>
                                                </div>
                                                <div class="meta">
                                                    <h3>{{ translate('Squad') }}</h3>
                                                    <p>
                                                        {{ translate('Pay via Squad; you can pay with your credit card if you don\'t have a Squad account.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="payment-process-right">
                            <div class="action-box">
                                <div class="intructions-block">
                                    <h2>{{ translate('Choose a payment method') }}</h2>
                                    <p>
                                        {{ translate('Please select a payment method to continue.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="checkout-next" class="payment-process-navigation has-text-centered">
                        <a class="button primary-btn raised is-bold">{{ translate('Continue') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://checkout.squadco.com/widget/squad.min.js"></script>
@endsection