<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    @includeWhen(true, 'backend.layouts.components.meta')
    <!-- Page Title  -->
    <title>{{ translate('429 | TOO MANY REQUESTS') }}</title>
    <!-- StyleSheets  -->
    @includeWhen(true, 'backend.layouts.components.css')
</head>

<body class="nk-body bg-white npc-default pg-error">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle wide-md mx-auto">
                        <div class="nk-block-content nk-error-ld text-center">
                            <img class="nk-error-gfx" src="./images/gfx/error-429.svg" alt="">
                            <div class="wide-xs mx-auto">
                                <h3 class="nk-error-title">{{ translate('TOO MANY REQUESTS') }}</h3>
                                <p class="nk-error-text">{{ translate('You may have mistyped the address or the page may have moved') }}.</p>
                                <a href="{{ route('frontend') }}" class="btn btn-lg btn-secondary mt-2">{{ translate('Back To Home') }}</a>
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="./assets/js/bundle.js?ver=2.4.0"></script>
    <script src="./assets/js/scripts.js?ver=2.4.0"></script>

</html>