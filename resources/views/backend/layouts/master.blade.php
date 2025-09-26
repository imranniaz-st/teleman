
<!DOCTYPE html>
<html lang="en" class="js">

<head>
    @includeWhen(true, 'backend.layouts.components.meta')
    <!-- Page Title  -->
    <title>@yield('title')</title>
    <!-- StyleSheets  -->
    @includeWhen(true, 'backend.layouts.components.css')
</head>

<body class="nk-body bg-white @if(teleman_config('dashboard_ui') == 'EXTENDED') npc-default has-apps-sidebar has-sidebar @else npc-subscription has-aside @endif" id="body">

    <div class="nk-app-root">

        @if (teleman_config('dashboard_ui') == 'EXTENDED')
            @includeWhen(true, 'backend.layouts.components.collapsed-sidebar')
        @endif

        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap {{ search_side_menu(Route::CurrentRouteName()) == false ? 'pl-30' : null }}">

                @if (teleman_config('dashboard_ui') == 'EXTENDED')
                    @includeWhen(true, 'backend.layouts.components.expand-topbar')
                    @includeWhen(true, 'backend.layouts.components.expand-sidebar')
                @else
                    @includeWhen(true, 'backend.layouts.components.topbar')
                @endif
                
                <!-- main header @e -->
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="@if(teleman_config('dashboard_ui') == 'EXTENDED') container-fluid @else container wide-xl @endif">
                        <div class="nk-content-inner">

                            @if (teleman_config('dashboard_ui') == 'CONTAINER')
                                @includeWhen(true, 'backend.layouts.components.sidebar')
                                <!-- .nk-aside -->
                            @endif

                                <div class="nk-content-body">

                                    @if (teleman_config('dashboard_ui') == 'CONTAINER')
                                    <div class="nk-content-wrap">
                                        <div class="components-preview wide-md mx-auto">
                    
                                            <div class="nk-block nk-block-lg">
                                                <div class="nk-block-head">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="title nk-block-title">@yield('title')</h4>
                                                    </div>
                                                </div>

                                                {{-- error messages --}}
                                                @if ($errors->any())
                                                    @foreach ($errors->all() as $error)
                                                        <div class="alert alert-pro alert-danger">
                                                            <div class="alert-text">
                                                                <h6>{{ $error }}</h6>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                {{-- error messages::END --}}
                                                    @yield('content')
                                            </div>

                                        </div><!-- .components-preview -->
                                    </div>

                                        {{-- footer --}}
                                        <div class="nk-footer">
                                            <div class="container wide-xl">
                                                <div class="nk-footer-wrap g-2">
                                                    <div class="nk-footer-copyright"> 
                                                        © {{ Carbon\Carbon::now()->year }} {{ appName() }} {{ translate('v') . env('VERSION') }}
                                                    </div>
                                                        
                                                    <div id="google_translate_element"></div>
                                                
                                                </div>
                                            </div>
                                        </div>
                                        {{-- footer::ENS --}}
                                    @else
                                        <div class="nk-block">

                                            <div class="nk-block-head">
                                                <div class="nk-block-head-content">
                                                    <h4 class="nk-block-title page-title">@yield('title')</h4>
                                                </div>
                                            </div>

                                            <div class="row g-gs">

                                                {{-- error messages --}}
                                                @if ($errors->any())
                                                    @foreach ($errors->all() as $error)
                                                        <div class="alert alert-pro alert-danger">
                                                            <div class="alert-text">
                                                                <h6>{{ $error }}</h6>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                {{-- error messages::END --}}

                                                @yield('content')
                                            </div>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content @e -->
            </div>
            <!-- wrap @e -->

        </div>
        <!-- main @e -->
    </div>
    <!-- nk-app-root @e -->

    {{-- Alert user --}}
    @includeWhen(true, 'backend.layouts.components.alert_user')
    {{-- Alert user::ENDS --}}

    {{-- Floaring button --}}
    @includeWhen(false, 'backend.layouts.components.floating')
    <!-- app-root @e -->
    <!-- JavaScript -->
    @includeWhen(true, 'backend.layouts.components.js')
</body>

</html>