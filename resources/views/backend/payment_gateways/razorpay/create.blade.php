@extends('backend.layouts.master')

@section('title')
    {{ translate('Razorpay Setup') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="card card-bordered">
    <div class="card-inner">
        
        <form action="{{ route('razorpay.payment.setup') }}" class="gy-3 form-validate is-alter" 
                method="GET" enctype="multipart/form-data" autocomplete="off">
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="razorpay_key">{{ translate('Razorpay Key') }}</label>
                        <span class="form-note">{{ translate('Specify the razorpay key') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="razorpay_key" 
                                    id="razorpay_key" 
                                    value="{{ env('RAZORPAY_KEY') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="razorpay_key">
                                {{ translate('Razorpay Key') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="razorpay_secret">{{ translate('Razorpay Secret') }}</label>
                        <span class="form-note">{{ translate('Specify the razorpay auth token') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="razorpay_secret" 
                                    id="razorpay_secret" 
                                    value="{{ env('RAZORPAY_SECRET') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="razorpay_secret">
                                {{ translate('Razorpay Secret') }}
                            </label>
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
                            <input type="checkbox" class="custom-control-input" value="1" name="RAZORPAY" id="site-off" {{ env('RAZORPAY') == "YES" ? 'checked' : null }}>
                            <label class="custom-control-label" for="site-off">{{ env('RAZORPAY') == "YES" ? 'Online' : 'Offline' }}</label>
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