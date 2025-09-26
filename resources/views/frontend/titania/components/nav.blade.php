<nav class="navbar navbar-wrapper navbar-default navbar-fade is-transparent">
            <div class="container">
                <!-- Brand -->
                <div class="navbar-brand">
                    <a class="navbar-item" href="{{ route('frontend') }}">
                        <img class="switcher-logo" src="{{ logo() }}" alt="{{ appName() }}">
                    </a>

                    <!-- Responsive toggle -->
                    <div class="custom-burger" data-target="">
                        <a class="responsive-btn" href="javascript:;">
                            <span class="menu-toggle">
                                <span class="icon-box-toggle">
                                    <span class="rotate">
                                        <i class="icon-line-top"></i>
                                        <i class="icon-line-center"></i>
                                        <i class="icon-line-bottom"></i>
                                    </span>
                                </span>
                            </span>
                        </a>
                    </div>
                    <!-- /Responsive toggle -->
                </div>

                <!-- Navbar menu -->
                <div class="navbar-menu">
                    <!-- Navbar Start -->
                    <div class="navbar-start" hx-boost="true">
                        <!-- Navbar item -->
                        @forelse(menus('primary menu') as $menu)
                            @if ($menu != null)
                                <a class="navbar-item is-slide" href="{{ $menu['link'] ?? 'javascript:;' }}" hx-push-url="true">
                                    {{ $menu['label'] ?? null }}
                                </a>
                            @endif
                        @empty

                        @endforelse

                        <a class="navbar-item is-slide" href="{{ route('frontend.pricing') }}" hx-push-url="true">
                            {{ translate('Pricing') }}
                        </a>

                        <a class="navbar-item is-slide" href="{{ route('frontend.page.blogs') }}" hx-push-url="true">
                            {{ translate('Blogs') }}
                        </a>
                       
                        @guest
                            @if(Route::has('login'))
                                <a class="navbar-item is-slide" href="{{ route('login') }}" @if (env('DEMO') == "YES") target="_blank" @endif>
                                    {{ translate('Login') }}
                                </a>
                            @endif
                        @endguest
                        
                    </div>

                    <!-- Navbar end -->
                    <div class="navbar-end">
                        <!-- Signup button -->
                        <div class="navbar-item">
                            @auth
                            <a href="{{ route('backend') }}"
                                class="button button-cta btn-outlined is-bold btn-align primary-btn raised">
                                {{ translate('Dashboard') }}
                            </a>
                            @endauth
                            @guest
                            <a href="{{ route('login') }}"
                                class="button button-cta btn-outlined is-bold btn-align primary-btn raised">
                                {{ translate('Login') }}
                            </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </nav>