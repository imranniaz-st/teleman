<!DOCTYPE html>
<html lang="en" class="js">

<head>
    @includeWhen(true, 'backend.layouts.components.meta')
    <!-- Page Title  -->
    <title>{{ appName() }} | {{ translate('Login') }}</title>
    <!-- StyleSheets  -->
    @includeWhen(true, 'backend.layouts.components.css')
</head>

<body class="nk-body bg-white npc-default pg-auth">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content justify-content-center">
                        <div class="nk-block-area nk-block-area-column bg-white">
                            <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                                <a href="javascript:;" class="toggle btn-white btn btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
                            </div>
                            <div class="nk-block nk-block-middle nk-auth-body">
                                <div class="brand-logo pb-5">
                                    <a href="{{ route('frontend') }}" class="logo-link">
                                        <img class="logo-light logo-img logo-img-lg" src="{{ logo() }}" srcset="{{ logo() }} 2x" alt="logo">
                                        <img class="logo-dark logo-img logo-img-lg" src="{{ logo() }}" srcset="{{ logo() }} 2x" alt="logo-dark">
                                    </a>
                                </div>
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title h5">{{ translate('Sign-In') }}</h5>
                                        <div class="nk-block-des">
                                            <p>{{ translate('Access the') }} {{ application('site_name') }} {{ translate('using your email and password.') }}</p>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <form action="{{ route('login') }}" method="post" id="login_form" autocomplete="off">
                                    @csrf

                                    
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <input type="email" 
                                                name="email" 
                                                class="form-control form-control-xl form-control-outlined @error('email') is-invalid @enderror" 
                                                id="email"
                                                value="{{ old('email') }}" required="" autocomplete="off">

                                                <label class="form-label-outlined" for="email">{{ translate('Enter your email address') }}</label>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                        </div>
                                    </div><!-- .foem-group -->

                                    <div class="form-group">
                                        <div class="form-control-wrap text-right mb-2">
                                            
                                            @if (Route::has('password.request'))
                                                <a class="link link-primary link-sm" tabindex="-1" href="{{ route('password.request') }}">{{ translate('Forgot Password?') }}</a>
                                            @endif
                                        </div>
                                        <div class="form-control-wrap">
                                            
                                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>

                                            <input type="password" 
                                                   class="form-control form-control-xl form-control-outlined @error('password') is-invalid @enderror" 
                                                   id="password" 
                                                   name="password" required autocomplete="off">

                                            <label class="form-label-outlined" for="password">{{ translate('Enter your password') }}</label>

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                            
                                        </div>

                                        
                                    </div><!-- .foem-group -->

                                    {{-- @if (application('google_recaptcha_mode') == 'YES')
                                        <div class="form-group form-control-wrap">
                                            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                            <div class="g-recaptcha" id="feedback-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY')  }}"></div>
                                            @error('g-recaptcha-response')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @endif --}}

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-secondary btn-block">{{ translate('Sign in') }}</button>
                                    </div>

                                    @if (env('DEMO') == "YES")

                                        <table class="table table-striped table-hover">
                                            <tr>
                                                <td>
                                                    <p onclick="demoAdmin()">{{ translate('email') }}: <strong>admin@mail.com</strong></p>
                                                    <p onclick="demoAdmin()">{{ translate('password') }}: <strong>12345678</strong></p>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-secondary justify-content-center" onclick="demoAdmin()" type="button">Copy</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p onclick="demoCustomer()">{{ translate('email') }}: <strong>customer@mail.com</strong></p>
                                                    <p onclick="demoCustomer()">{{ translate('password') }}: <strong>12345678</strong></p>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-secondary justify-content-center" onclick="demoCustomer()" type="button">{{ translate('Copy') }}</button>
                                                </td>
                                            </tr>
                                        </table>

                                    @endif


                                </form><!-- form -->
                                <div class="form-note-s2 pt-4 text-center"> {{ translate('New on our platform?') }} <a href="{{ route('frontend.pricing') }}"> <strong>{{ translate('Create an account') }}</strong></a>
                                </div>
                                <div class="text-center pt-4">
                                    <h6 class="overline-title overline-title-sap"><span>{{ translate('OR') }}</span></h6>
                                </div>
                               
                                <div class="text-center mt-5">
                                    <span class="fw-500">{{ translate("I don't have an account?") }} <a href="{{ route('frontend.pricing') }}"> <strong>{{ translate('Try') }} {{ env('TRIAL_PERIOD_DAYS') }} {{ Str::pluralStudly('days', env('TRIAL_PERIOD_DAYS')) }} {{ translate('free trial') }}</strong></a></span>
                                </div>
                            </div><!-- .nk-block -->
                          
                        </div><!-- .nk-split-content -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    @includeWhen(true, 'backend.layouts.components.js')

</html>