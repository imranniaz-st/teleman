@extends('backend.layouts.master')

@section('title')
    {{ translate('SSL COMMERZ') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="card card-bordered">
                    <div class="card-inner">
                       
                        <form action="{{ route('dashboard.sslcommerz.update') }}" class="gy-3 form-validate is-alter" method="GET" enctype="multipart/form-data">
                            
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="form-label" for="site-name">{{ translate('API Domain URL') }}</label>
                                        <span class="form-note">{{ translate('Specify the api domain url') }}.</span>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" name="API_DOMAIN_URL" id="site-name" value="{{ env('API_DOMAIN_URL') }}" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="form-label" for="store-id">{{ translate('STORE ID') }}</label>
                                        <span class="form-note">{{ translate('Specify the store id') }}.</span>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="store-id" name="STORE_ID" value="{{ env('STORE_ID') }}" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="form-label" for="store-password">{{ translate('STORE PASSWORD') }}</label>
                                        <span class="form-note">{{ translate('Specify the store password') }}.</span>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="store-password" name="STORE_PASSWORD" value="{{ env('STORE_PASSWORD') }}" required="">
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
                                            <input type="checkbox" class="custom-control-input" value="1" name="SSL_COMMERZ" id="site-off" {{ env('SSL_COMMERZ') == "YES" ? 'checked' : null }}>
                                            <label class="custom-control-label" for="site-off">{{ env('SSL_COMMERZ') == "YES" ? 'Online' : 'Offline' }}</label>
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