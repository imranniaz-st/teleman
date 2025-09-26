<div class="nk-apps-sidebar is-theme">
    <div class="nk-apps-brand">
        <a href="{{ route('backend') }}" class="logo-link">
            <img class="logo-light logo-img" src="{{ asset(application('site_favicon')) }}"
                srcset="{{ asset(application('site_favicon')) }} 2x" alt="logo">
            <img class="logo-dark logo-img" src="{{ asset(application('site_favicon')) }}"
                srcset="{{ asset(application('site_favicon')) }} 2x" alt="logo-dark">
        </a>
    </div>
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-body">
            <div class="nk-sidebar-content" data-simplebar>
                <div class="nk-sidebar-menu">
                    <ul class="nk-menu apps-menu">

                        @foreach(extended_menu() as $menuKey => $menu)
                            @can($menu['permission'])
                                <li class="nk-menu-item @if(isset($menu['route_name'])) {{ request()->routeIs($menu['route_name']) ? 'active current-page' : '' }} @endif">
                                    <a href="{{ isset($menu['route_name']) ? route($menu['route_name'], $menu['params']) : 'javascript:;' }}" 
                                        class="nk-menu-link" title="{{ $menu['title'] }}" id="{{ $menuKey }}"
                                        data-bs-original-title="{{ $menu['title'] }}" aria-label="{{ $menu['title'] }}">
                                        <span class="nk-menu-icon">
                                            <em class="icon ni {{ $menu['icon'] }}"></em>
                                        </span>
                                    </a>
                                </li>
                            @endcan
                        @endforeach

                    </ul>
                </div>

                <div class="nk-sidebar-footer">
                    <ul class="nk-menu nk-menu-md">
                        <li class="nk-menu-item">
                            <a href="{{ route('dashboard.profile.information') }}"
                                class="nk-menu-link" title="{{ translate('Settings') }}">
                                <span class="nk-menu-icon">
                                    <em class="icon ni ni-setting"></em>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="nk-sidebar-profile nk-sidebar-profile-fixed dropdown">
                <a href="javascript:;" class="toggle" data-target="profileDD">
                    <div class="user-avatar">
                        <span><em class="icon ni ni-user-alt"></em></span>
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-md m-1 nk-sidebar-profile-dropdown" data-content="profileDD">
                    <div class="dropdown-inner user-card-wrap d-none d-md-block">
                        <div class="user-card">
                            <div class="user-avatar"><span><em class="icon ni ni-user-alt"></em></span></div>
                            <div class="user-info">
                                <span class="lead-text">{{ Str::headline(Auth::user()->name) }}</span>
                                <span class="sub-text text-soft">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-inner">
                        <ul class="link-list">
                            <li>
                                <a href="{{ route('dashboard.profile.information') }}">
                                    <em class="icon ni ni-user-alt"></em>
                                    <span>{{ translate('View Profile') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="dropdown-inner">
                        <ul class="link-list">
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <em
                                        class="icon ni ni-signout"></em><span>{{ translate('Sign out') }}</span></a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
