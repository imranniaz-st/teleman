@extends('backend.layouts.master')

@section('title')
{{ translate('MojSMS Setup') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="card card-bordered">
    <div class="card-inner">

        <form action="{{ route('dashboard.mojsms.store') }}" class="gy-3 form-validate is-alter"
            method="POST" enctype="multipart/form-data" autocomplete="off">

            @csrf

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label"
                            for="bearer_token">{{ translate('MojSMS Token') }}</label>
                        <span
                            class="form-note">{{ translate('Specify the mojsms token') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg form-control-outlined"
                                name="bearer_token" id="bearer_token"
                                value="{{ $mojsms->bearer_token ?? null }}" required="">
                            <label class="form-label-outlined" for="user_email">
                                {{ translate('MojSMS Token') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label"
                            for="sender_id">{{ translate('MojSMS Sender ID') }}</label>
                        <span
                            class="form-note">{{ translate('Specify the mojsms token') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg form-control-outlined"
                                name="sender_id" id="sender_id" value="{{ $mojsms->sender_id ?? null }}"
                                required="">
                            <label class="form-label-outlined" for="user_email">
                                {{ translate('MojSMS Sender ID') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-7 offset-lg-5">
                    <div class="form-group mt-2">
                        <button type="submit"
                            class="btn btn-lg btn-secondary">{{ translate('Save Configuration') }}</button>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div><!-- card --> 

<div class="card card-bordered">
    <div class="card-inner">
        

        <div id="accordion-1" class="accordion accordion-s2">
            <div class="accordion-item">
                <a href="#" class="accordion-head" data-toggle="collapse" data-target="#accordion-item-1-1">
                    <h6 class="title">{{translate('MojSMS Setup')}}</h6>
                    <span class="accordion-icon"></span>
                </a>
                <div class="accordion-body collapse show" id="accordion-item-1-1" data-parent="#accordion-1">
                    <div class="accordion-inner">
                        <p>
                            {{ translate('Create a new MojSMS Telekom account') }} <a href="https://mojsms.io/register" target="_blank"> {{ translate('sign into an existing MojSMS account') }} </a> 
                            <br>
                            {{ translate('After login with you will find API Key on left menu "Developers". Use Live API Key for Testing mode. Be shure to have enought balance in your account.') }}
                            <br>
                            {{ translate('Finally, copy the API Key and paste from your REST API KEY to your script URL field. Finally, click on Save button.') }} 
                        </p>
                    </div>
                </div>
            </div>
        </div>  


    </div>
</div>

@endsection

@section('js')

@endsection
