@extends('backend.layouts.master')

@section('title')
    {{ translate('SMTP Settings') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="card card-bordered">
    <div class="card-inner">
        <form action="{{ route('dashboard.smtp.store') }}" class="gy-3 form-validate is-alter" method="GET">
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label">{{ translate('SMTP Driver') }}</label>
                        <span class="form-note">{{ translate('Choose mail driver') }}. {{ translate('Ex: smtp, sendmail') }}</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" name="driver" id="site-email" value="{{ env('MAIL_MAILER') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Host') }}</label>
                        <span class="form-note">{{ translate('According to your server mail client') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" name="host" id="site-copyright" value="{{ env('MAIL_HOST') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Port') }}</label>
                        <span class="form-note">{{ translate('Mail port as') }} 587, 465, 25, 2525.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" name="port" id="site-copyright" value="{{ env('MAIL_PORT') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Username') }}</label>
                        <span class="form-note">{{ translate('Mail account username') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" name="username" id="site-copyright" value="{{ env('MAIL_USERNAME') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Password') }}</label>
                        <span class="form-note">{{ translate('Mail account password') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" name="password" id="site-copyright" value="{{ env('MAIL_PASSWORD') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Email From Address') }}</label>
                        <span class="form-note">{{ translate('Set sender email address') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" name="from" id="site-copyright" value="{{ env('MAIL_FROM_ADDRESS') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Email From Name') }}</label>
                        <span class="form-note">{{ translate('Set sender name as email heading') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" name="from_name" id="site-copyright" value="{{ env('MAIL_FROM_NAME') }}">
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Encryption') }}</label>
                        <span class="form-note">{{ translate('Mail encryption as') }} 'ssl' {{ translate('if you face issue with') }} 'tls'.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <select class="form-select" single="single" data-placeholder="Select Encryption Type" name="encryption">
                                <option value="" {{ env('MAIL_ENCRYPTION') === '' ? 'selected' : null }}>{{ translate('No Encryption') }}</option>
                                <option value="tls" {{ env('MAIL_ENCRYPTION') === 'tls' ? 'selected' : null }}>{{ translate('TLS') }}</option>
                                <option value="ssl" {{ env('MAIL_ENCRYPTION') === 'ssl' ? 'selected' : null }}>{{ translate('SSL') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-lg-7 offset-lg-5">
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Submit') }}</button>
                        <button type="button" class="btn btn-lg btn-secondary" data-toggle="modal" data-target="#modalForm">{{ translate('Test Configuration') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div><!-- card -->

<div class="modal fade" tabindex="-1" id="modalForm">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ translate('Test SMTP Configuration') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('dashboard.smtp.test') }}" 
                        class="form-validate is-alter" 
                        method="GET">

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="email">{{ translate('Recipient Email') }} *</label>
                                <span class="form-note">{{ translate('Specify the recipient email') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="email" 
                                            class="form-control" 
                                            id="email" 
                                            name="email" 
                                            value="{{ old('email') }}"
                                            placeholder="{{ translate('Recipient Email') }}"
                                            required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3">
                        <div class="col-lg-7 offset-lg-5">
                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Send Mail') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
            
@endsection

@section('js')
    
@endsection