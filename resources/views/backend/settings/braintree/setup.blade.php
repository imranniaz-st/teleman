@extends('backend.layouts.master')

@section('title')
    {{ translate('Braintree') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="card card-bordered">
                    <div class="card-inner">
                       
                        <form action="{{ route('dashboard.braintree.update') }}" class="gy-3 form-validate is-alter" method="GET" enctype="multipart/form-data">
                            
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="form-label" for="site-name">{{ translate('Environment') }}</label>
                                        <span class="form-note">{{ translate('Specify the environment sandbox or production') }}.</span>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" name="BT_ENVIRONMENT" id="site-name" value="{{ env('BT_ENVIRONMENT') }}" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="form-label" for="store-id">{{ translate('Merchant ID') }}</label>
                                        <span class="form-note">{{ translate('Specify the merchant id') }}.</span>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="store-id" name="BT_MERCHANT_ID" value="{{ env('BT_MERCHANT_ID') }}" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="form-label" for="store-password">{{ translate('Public Key') }}</label>
                                        <span class="form-note">{{ translate('Specify the public key') }}.</span>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="store-password" name="BT_PUBLIC_KEY" value="{{ env('BT_PUBLIC_KEY') }}" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="form-label" for="store-password">{{ translate('Private Key') }}</label>
                                        <span class="form-note">{{ translate('Specify the private key') }}.</span>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="store-password" name="BT_PRIVATE_KEY" value="{{ env('BT_PRIVATE_KEY') }}" required="">
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
                                            <input type="checkbox" class="custom-control-input" value="1" name="BRAINTREE" id="site-off" {{ env('BRAINTREE') == "YES" ? 'checked' : null }}>
                                            <label class="custom-control-label" for="site-off">{{ env('BRAINTREE') == "YES" ? 'Online' : 'Offline' }}</label>
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