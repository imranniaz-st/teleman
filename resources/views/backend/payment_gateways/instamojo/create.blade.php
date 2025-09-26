@extends('backend.layouts.master')

@section('title')
    {{ translate('Instamojo Setup') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="card card-bordered">
    <div class="card-inner">
        
        <form action="{{ route('payment.setup.instamojo.store') }}" class="gy-3 form-validate is-alter" 
                method="GET" enctype="multipart/form-data" autocomplete="off">
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="instamojo_api_key">{{ translate('Instamojo Api Key') }}</label>
                        <span class="form-note">{{ translate('Specify the instamojo api key') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="instamojo_api_key" 
                                    id="instamojo_api_key" 
                                    value="{{ env('IM_AUTH_TOKEN') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="instamojo_api_key">
                                {{ translate('Instamojo Api Key') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="instamojo_auth_token">{{ translate('Instamojo Auth Token') }}</label>
                        <span class="form-note">{{ translate('Specify the instamojo auth token') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="instamojo_auth_token" 
                                    id="instamojo_auth_token" 
                                    value="{{ env('IM_AUTH_TOKEN') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="instamojo_auth_token">
                                {{ translate('Instamojo Auth Token') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="instamojo_url">{{ translate('Instamojo URL') }}</label>
                        <span class="form-note">{{ translate('Specify the instamojo payment url') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                    class="form-control form-control-lg form-control-outlined" 
                                    name="instamojo_url" 
                                    id="instamojo_url" 
                                    value="{{ env('IM_URL') ?? '' }}" 
                                    required="">
                            <label class="form-label-outlined" for="instamojo_url">
                                {{ translate('Instamojo URL') }}
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
                            <input type="checkbox" class="custom-control-input" value="1" name="INSTAMOJO" id="site-off" {{ env('INSTAMOJO') == "YES" ? 'checked' : null }}>
                            <label class="custom-control-label" for="site-off">{{ env('INSTAMOJO') == "YES" ? 'Online' : 'Offline' }}</label>
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