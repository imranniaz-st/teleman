<!DOCTYPE html>
<html lang="en" class="js">

<head>
    @includeWhen(true, 'backend.layouts.components.meta')
    <!-- Page Title  -->
    <title>{{ appName() }} | {{ translate('Verify Account') }}</title>
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
                                    <h5 class="nk-block-title">{{ translate('Enter OTP Number') }}</h5>
                                    <div class="nk-block-des">
                                        <p>{{ translate('If you do not have your OTP, well, then resend new OTP by') }} <a href="{{ route('email.verification.resend') }}">{{ translate('click here') }}</a>.</p>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('email.verification.code.match') }}" method="GET">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="default-01">{{ translate('Enter OTP') }}</label>
                                        <a class="link link-primary link-sm" href="{{ route('email.verification.resend') }}">{{ translate('Resend OTP') }}</a>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" name="otp" id="default-01" placeholder="Enter your OTP">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg btn-secondary btn-block">{{ translate('Verify') }}</button>
                                </div>
                            </form>
                            
                            <div class="form-note-s2 pt-5">
                                <a href="{{ route('login') }}"><strong>{{ translate('Return to login') }}</strong></a>
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