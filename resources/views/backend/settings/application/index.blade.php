@extends('backend.layouts.master')

@section('title')
{{ translate('APPLICATION') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="card card-bordered">
    <div class="card-inner">
        <form action="{{ route('dashboard.application.update') }}" class="gy-3 form-validate is-alter" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-title">{{ translate('Site Name') }}</label>
                        <span class="form-note">{{ translate('Specify the name of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="site-title" 
                                   name="site_name" 
                                   value="{{ application('site_name') }}"
                                   required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-title">{{ translate('Site Email') }}</label>
                        <span class="form-note">{{ translate('Specify the email of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="email" 
                                   class="form-control" 
                                   id="site-email" 
                                   name="site_email" 
                                   value="{{ application('site_email') }}"
                                   required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-phone">{{ translate('Site Phone') }}</label>
                        <span class="form-note">{{ translate('Specify the Phone of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="site-phone" 
                                   name="site_phone" 
                                   value="{{ application('site_phone') }}"
                                   required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="test-phone">{{ translate('Test Phone Number') }}</label>
                        <span class="form-note">{{ translate('Specify the testing phone number of your twilio account') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="test-phone" 
                                   name="test_phone" 
                                   value="{{ application('test_phone') }}"
                                   required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site_logo">{{ translate('Site Logo') }}</label>
                        <span class="form-note">{{ translate('Specify the logo of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <img src="{{ asset(application('site_logo')) }}" class="img-fluid mb-3" alt="">
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input" 
                                       name="site_logo" 
                                       id="site_logo">
                                <label class="custom-file-label" for="site_logo">{{ translate('Choose file') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site_dark_logo">{{ translate('Site Logo (Dark Mode)') }}</label>
                        <span class="form-note">{{ translate('Specify the dark logo of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <img src="{{ asset(application('site_dark_logo')) }}" class="img-fluid mb-3" alt="">
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input" 
                                       name="site_dark_logo" 
                                       id="site_dark_logo">
                                <label class="custom-file-label" for="site_dark_logo">{{ translate('Choose file') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site_favicon">{{ translate('Site Favicon') }}</label>
                        <span class="form-note">{{ translate('Specify the site favicon of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <img src="{{ asset(application('site_favicon')) }}" class="img-fluid mb-3" alt="">
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input" 
                                       name="site_favicon" 
                                       id="customFile">
                                <label class="custom-file-label" for="site_favicon">{{ translate('Choose file') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site_trailer_thumbnail">{{ translate('Site Trailer Thumbnail') }}</label>
                        <span class="form-note">{{ translate('Specify the site trailer thumbnail of the video') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <img src="{{ asset(application('site_trailer_thumbnail')) }}" width="100" class="img-fluid mb-3" alt="">
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input" 
                                       name="site_trailer_thumbnail" 
                                       id="customFile">
                                <label class="custom-file-label" for="site_trailer_thumbnail">{{ translate('Choose file') }}</label>
                                <small>{{ translate('thumbnail size must be: 860x788') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site_trailer_url">{{ translate('Site Tailer URL') }}</label>
                        <span class="form-note">{{ translate('Specify the site trailer url of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="site_trailer_url" 
                                   name="site_trailer_url" 
                                   value="{{ application('site_trailer_url') }}">
                                   <small>{{ translate('Ex') }}: <b>https://domain.com/teleman.mp4</b></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="customFile">{{ translate('Site Gateway Supports') }}</label>
                        <span class="form-note">{{ translate('Specify the gateway of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <img src="{{ asset(application('site_gateway_supports')) }}" class="img-fluid mb-3" alt="">
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input" 
                                       name="site_gateway_supports" 
                                       id="site_gateway_supports">
                                <label class="custom-file-label" for="site_gateway_supports">{{ translate('Choose file') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-facebook">{{ translate('Site Facebook') }}</label>
                        <span class="form-note">{{ translate('Specify the facebook profile of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="site-facebook" 
                                   name="site_facebook" 
                                   value="{{ application('site_facebook') }}"
                                   required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-instagram">{{ translate('Site Instagram') }}</label>
                        <span class="form-note">{{ translate('Specify the instagram profile of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="site-instagram" 
                                   name="site_instagram" 
                                   value="{{ application('site_instagram') }}"
                                   required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-twitter">{{ translate('Site Twitter') }}</label>
                        <span class="form-note">{{ translate('Specify the twitter profile of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="site-twitter" 
                                   name="site_twitter" 
                                   value="{{ application('site_twitter') }}"
                                   required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-youtube">{{ translate('Site Youtube') }}</label>
                        <span class="form-note">{{ translate('Specify the youtube profile of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="site-youtube" 
                                   name="site_youtube" 
                                   value="{{ application('site_youtube') }}"
                                   required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-linkedin">{{ translate('Site Linkedin') }}</label>
                        <span class="form-note">{{ translate('Specify the linkedin profile of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="site-linkedin" 
                                   name="site_linkedin" 
                                   value="{{ application('site_linkedin') }}"
                                   required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-colors">{{ translate('Site Color') }}</label>
                        <span class="form-note">{{ translate('Specify the color of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text"
                                   class="form-control" 
                                   id="site-colors" 
                                   name="site_colors" 
                                   value="{{ application('site_colors') }}">
                        </div>
                        <small>{{ translate('Example') }}: '#81ecec'</small>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-colors">{{ translate('Timezone') }}</label>
                        <span class="form-note">{{ translate('Specify the colors of your website banner') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" single="single"
                                    data-placeholder="Select Timezone" name="site_timezone" required="">
                                    @forelse (timeZone() as $key => $zone)
                                        <option value="{{ $key }}" {{ $key == env('TIMEZONE') ? 'selected' : null }}>{{ $key }} - {{ $zone }}</option>
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
                        <label class="form-label" for="site-colors">{{ translate('Dashboard UI') }}</label>
                        <span class="form-note">{{ translate('Specify the dashboard UI') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" single="single"
                                    data-placeholder="Select Dashboard" name="site_dashboard" required="">
                                        <option value="EXTENDED" {{ env('DASHBOARD_UI') == 'EXTENDED' ? 'selected' : null }}>{{ translate('EXTENDED') }}</option>
                                        {{-- <option value="CONTAINER" {{ env('DASHBOARD_UI') == 'CONTAINER' ? 'selected' : null }}>{{ translate('CONTAINER') }}</option> --}}
                                   
                                </select>
                            </div>
                        </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-colors">{{ translate('Frontend Theme') }}</label>
                        <span class="form-note">{{ translate('Specify the frontend theme status') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" single="single"
                                    data-placeholder="Select Status" name="site_frontend_theme" required="">
                                        <option value="ACTIVE" {{ env('FRONTEND_THEME') == 'ACTIVE' ? 'selected' : null }}>{{ translate('ACTIVE') }}</option>
                                        <option value="DEACTIVE" {{ env('FRONTEND_THEME') == 'DEACTIVE' ? 'selected' : null }}>{{ translate('DEACTIVE') }}</option>
                                   
                                </select>
                            </div>
                        </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="google_recaptcha_key">{{ translate('Google ReCaptcha Key') }}</label>
                        <span class="form-note">{{ translate('Specify the google recaptcha key.') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text"
                                   class="form-control" 
                                   id="google_recaptcha_key" 
                                   name="google_recaptcha_key" 
                                   value="{{ application('google_recaptcha_key') }}">
                        </div>
                        <small>{{ translate('Visit ') }}<a href="https://www.google.com/recaptcha/admin/create" target="_blank" class="text-danger">{{ translate('reCAPTCHA admin panel') }}</a> {{ translate( 'and create a project. ') }} <a href="https://codingdriver.com/wp-content/uploads/2021/09/Google-recaptch-v2-add-domain-1024x760.png" target="_blank" class="text-danger">{{ translate('Select version reCAPTCHA v2.') }}</a></small>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="google_recaptcha_secret_key">{{ translate('Google ReCaptcha Secret Key') }}</label>
                        <span class="form-note">{{ translate('Specify the google recaptcha secret key') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text"
                                   class="form-control" 
                                   id="google_recaptcha_secret_key" 
                                   name="google_recaptcha_secret_key" 
                                   value="{{ application('google_recaptcha_secret_key') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="google_recaptcha_mode">{{ translate('Google ReCaptch Mode') }}</label>
                        <span class="form-note">{{ translate('Specify the google recaptcha mode') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" single="single"
                                    data-placeholder="Select Status" name="google_recaptcha_mode" required="">
                                        <option value="YES" {{ application('google_recaptcha_mode') == 'YES' ? 'selected' : null }}>{{ translate('ACTIVE') }}</option>
                                        <option value="NO" {{ application('google_recaptcha_mode') == 'NO' ? 'selected' : null }}>{{ translate('DEACTIVE') }}</option>
                                   
                                </select>
                            </div>
                        </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="kyc">{{ translate('KYC Document Verification') }}</label>
                        <span class="form-note">{{ translate('Enable or disable KYC document verification') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" single="single"
                                    data-placeholder="Select Status" name="kyc" required="">
                                        <option value="YES" {{ application('kyc') == 'YES' ? 'selected' : null }}>{{ translate('ACTIVE') }}</option>
                                        <option value="NO" {{ application('kyc') == 'NO' ? 'selected' : null }}>{{ translate('DEACTIVE') }}</option>
                                </select>
                            </div>
                        </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="open_ai_key">{{ translate('OPEN AI Key') }}</label>
                        <span class="form-note">{{ translate('Specify the Open AI key') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text"
                                   class="form-control" 
                                   id="open_ai_key" 
                                   name="open_ai_key" 
                                   value="{{ application('open_ai_key') }}">
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
