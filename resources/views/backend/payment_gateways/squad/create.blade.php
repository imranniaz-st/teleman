@extends('backend.layouts.master')

@section('title')
    {{ translate('Squad Setup') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="card card-bordered">
    <div class="card-inner">
        
        <form action="{{ route('squad.store') }}" class="gy-3 form-validate is-alter" 
                method="GET" enctype="multipart/form-data" autocomplete="off">
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="squad_public_key">{{ translate('Squad Public Key') }}</label>
                        <span class="form-note">{{ translate('Specify the squad public key') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="squad_public_key" 
                                    id="squad_public_key" 
                                    value="{{ env('SQUAD_PUBLIC_KEY') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="squad_public_key">
                                {{ translate('Squad Public Key') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="squad_secret_key">{{ translate('Squad Secret Key') }}</label>
                        <span class="form-note">{{ translate('Specify the squad secret key') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="squad_secret_key" 
                                    id="squad_secret_key" 
                                    value="{{ env('SQUAD_SECRET_KEY') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="squad_secret_key">
                                {{ translate('Squad Secret Key') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="flutterwave_hash">{{ translate('Squad Currency') }}</label>
                        <span class="form-note">{{ translate('Specify the squad currency') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <select class="form-select form-control form-control-xl" name="squad_currency" required data-ui="xl" id="outlined-select">
                                <option value="NGN" {{ env('SQUAD_CURRENCY') == "NGN" ? 'selected' : null }}>{{ translate('Nigeria (NGN)') }}</option>
                                <option value="USD" {{ env('SQUAD_CURRENCY') == "USD" ? 'selected' : null }}>{{ translate('United States Dollar (USD)') }}</option>
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
                            <input type="checkbox" class="custom-control-input" value="1" name="squad" id="site-off" {{ env('SQUAD') == "YES" ? 'checked' : null }}>
                            <label class="custom-control-label" for="site-off">{{ env('SQUAD') == "YES" ? 'Online' : 'Offline' }}</label>
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