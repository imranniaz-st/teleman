@extends('backend.layouts.master')

@section('title')
    {{ translate('Paystack Setup') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="card card-bordered">
    <div class="card-inner">
        
        <form action="{{ route('paystack.store') }}" class="gy-3 form-validate is-alter" 
                method="GET" enctype="multipart/form-data" autocomplete="off">
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="paystack_public_key">{{ translate('Paystack Public Key') }}</label>
                        <span class="form-note">{{ translate('Specify the flutterwave public key') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="paystack_public_key" 
                                    id="paystack_public_key" 
                                    value="{{ env('PAYSTACK_PUBLIC_KEY') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="flutterwave_client_id">
                                {{ translate('Paystack Public Key') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="paystack_secret_key">{{ translate('Paystack Secret Key') }}</label>
                        <span class="form-note">{{ translate('Specify the paystack secret key') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="paystack_secret_key" 
                                    id="paystack_secret_key" 
                                    value="{{ env('PAYSTACK_SECRET_KEY') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="paystack_secret_key">
                                {{ translate('Paystack Secret Key') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="paystack_payment_url">{{ translate('Paystack Payment URL') }}</label>
                        <span class="form-note">{{ translate('Specify the paystack payment url') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="paystack_payment_url" 
                                    id="paystack_payment_url" 
                                    value="{{ env('PAYSTACK_PAYMENT_URL') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="paystack_payment_url">
                                {{ translate('Paystack Secret Hash') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="paystack_merchant_email">{{ translate('Paystack Merchant Email') }}</label>
                        <span class="form-note">{{ translate('Specify the paystack merchant email') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="paystack_merchant_email" 
                                    id="paystack_merchant_email" 
                                    value="{{ env('MERCHANT_EMAIL') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="paystack_merchant_email">
                                {{ translate('Paystack Merchant Email') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="flutterwave_hash">{{ translate('Paystack Merchant Currency') }}</label>
                        <span class="form-note">{{ translate('Specify the merchant currency') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <select class="form-select form-control form-control-xl" name="paystack_merchant_currency" required data-ui="xl" id="outlined-select">
                                <option value="GHS" {{ env('MERCHANT_CURRENCY') == "GHS" ? 'selected' : null }}>{{ translate('Ghana (GHS)') }}</option>
                                <option value="NGN" {{ env('MERCHANT_CURRENCY') == "NGN" ? 'selected' : null }}>{{ translate('Nigeria (NGN)') }}</option>
                                <option value="ZAR" {{ env('MERCHANT_CURRENCY') == "ZAR" ? 'selected' : null }}>{{ translate('South Africa (ZAR)') }}</option>
                                <option value="USD" {{ env('MERCHANT_CURRENCY') == "USD" ? 'selected' : null }}>{{ translate('United States Dollar (USD)') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-off">{{ translate('Maintanance Mode') }}</label>
                        <span class="form-note">{{ translate('Enable to make gateway offline') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" value="1" name="PAYSTACK" id="site-off" {{ env('PAYSTACK') == "YES" ? 'checked' : null }}>
                            <label class="custom-control-label" for="site-off">{{ env('PAYSTACK') == "YES" ? 'Online' : 'Offline' }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-7 offset-lg-5">
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Update') }}</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div><!-- card -->

@endsection

@section('js')
    
@endsection