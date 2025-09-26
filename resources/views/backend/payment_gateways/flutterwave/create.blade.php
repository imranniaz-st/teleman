@extends('backend.layouts.master')

@section('title')
    {{ translate('Flutterwave Setup') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="card card-bordered">
    <div class="card-inner">
        
        <form action="{{ route('payment.setup.flutterwave.store') }}" class="gy-3 form-validate is-alter" method="GET" enctype="multipart/form-data" autocomplete="off">
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="flutterwave_client_id">{{ translate('Flutterwave Public Key') }}</label>
                        <span class="form-note">{{ translate('Specify the flutterwave public key') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="flutterwave_client_id" 
                                    id="flutterwave_client_id" 
                                    value="{{ env('FLW_PUBLIC_KEY') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="flutterwave_client_id">
                                {{ translate('Flutterwave Public Key') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="flutterwave_secret">{{ translate('Flutterwave Secret Key') }}</label>
                        <span class="form-note">{{ translate('Specify the flutterwave secret key') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="flutterwave_secret" 
                                    id="flutterwave_secret" 
                                    value="{{ env('FLW_SECRET_KEY') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="flutterwave_secret">
                                {{ translate('Flutterwave Secret Key') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="flutterwave_hash">{{ translate('Flutterwave Secret Hash') }}</label>
                        <span class="form-note">{{ translate('Specify the flutterwave secret hash') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="flutterwave_hash" 
                                    id="flutterwave_hash" 
                                    value="{{ env('FLW_SECRET_HASH') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="flutterwave_hash">
                                {{ translate('Flutterwave Secret Hash') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="flutterwave_hash">{{ translate('Select Supported Currency') }}</label>
                        <span class="form-note">{{ translate('Specify the supported currency') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <select class="form-select form-control form-control-xl" name="FLW_CURRENCY" required data-ui="xl" id="outlined-select">
                                 @forelse (flutterwaveSupportedCountries() as $currency => $symbol)
                                <option value="{{ $symbol }}" 
                                        {{ env('FLW_CURRENCY') == $symbol ? 'selected' : null }}>
                                    {{ $currency }} - {{ $symbol }}
                                </option>
                                @empty
                                @endforelse
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
                            <input type="checkbox" class="custom-control-input" value="1" name="FLUTTERWAVE" id="site-off" {{ env('FLUTTERWAVE') == "YES" ? 'checked' : null }}>
                            <label class="custom-control-label" for="site-off">{{ env('FLUTTERWAVE') == "YES" ? 'Online' : 'Offline' }}</label>
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