<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">

            <div class="nk-menu-trigger d-xl-none ms-n1">
                <a href="javascript:;" class="nk-nav-toggle nk-quick-nav-icon" onclick="ToggleMobileSideBar()">
                    <em class="icon ni ni-menu"></em>
                </a>
            </div>

            <div class="nk-header-app-name">
                <div class="nk-header-app-logo">
                    <a href="{{ route('backend') }}" class="logo-link">
                        <img class="logo-light logo-img" src="{{ asset(application('site_favicon')) }}" srcset="{{ asset(application('site_favicon')) }}" alt="{{ appName() }}">
                        <img class="logo-dark logo-img" src="{{ asset(application('site_favicon')) }}" srcset="{{ asset(application('site_favicon')) }}" alt="{{ appName() }}">
                    </a>
                </div>
                <div class="nk-header-app-info">
                    <span class="lead-text">@yield('title')</span>
                </div>
            </div>

            <div class="nk-header-menu is-light">
                <div class="nk-header-menu-inner">
                    <ul class="nk-menu nk-menu-main">
                        <li class="nk-menu-item">
                            @can('customer')
                                <span class="nk-menu-text">
                                    <span class="badge badge-dim bg-outline-secondary">
                                        {{ PackageDetails(userSubscriptionData(Auth::user()->domain)->package_id)->name }} {{ translate('plan') }}
                                    </span>

                                    <span class="badge badge-dim bg-outline-secondary">
                                        {{ translate('Credits: ') }}{{ user_current_credit(Auth::id()) }}
                                    </span>
                                    
                                    <span class="badge badge-dim bg-outline-secondary">
                                        <a href="{{ route('frontend.pricing') }}">
                                            {{ translate('Upgrade plan') }}
                                        </a>
                                    </span>
                                </span>
                            @endcan
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{ route('dialer.index') }}" class="nk-menu-link">
                                {{ translate('Web Dialer') }}
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{ route('dashboard.campaign.index') }}" class="nk-menu-link">
                                {{ translate('Campaigns') }}
                            </a>
                        </li>

                        @can('admin')
                            
                        <li class="nk-menu-item has-sub">
                            <a href="javascript:;" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-text">
                                    {{ symbol() }} {{ curr() }}
                                </span>
                            </a>

                            <ul class="nk-menu-sub">
                                @foreach(allCurrencies() as $currency)
                                <li class="nk-menu-item">
                                    <a href="{{ route('switch.currency') }}" class="nk-menu-link"
                                        onclick="event.preventDefault();
                                        document.getElementById('{{$currency->symbol}}').submit();">
                                        <span class="nk-menu-text">
                                            {{$currency->name}} ({{$currency->symbol}})
                                        </span>
                                        <span>{{$currency->icon}} {{$currency->amount}}</span>
                                    </a>
                                    <form id="{{ $currency->symbol }}" class="d-none" action="{{ route('switch.currency') }}"
                                        method="GET">
                                        <input type="hidden" name="currency" value="{{ $currency->symbol }}">
                                    </form>
                                </li>
                                @endforeach
                            </ul>
                        </li>

                        @endcan
                    </ul>
                </div>
            </div>
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">

                    <li>
                        <a class="dark-switch"  
                            href="javascript:;" onclick="ChangeMode()">
                            <em class="icon ni ni-moon"></em>
                        </a>
                    </li>

                    <li>
                        <a class="nk-quick-nav-icon"  
                            href="{{ route('optimize') }}">
                            <em class="icon ni ni-monitor"></em>
                        </a>
                    </li>

                    <li class="dropdown hide-mb-sm">
                        <a data-bs-toggle="modal" href="{{ route('frontend') }}" target="_blank" class="nk-quick-nav-icon">
                            <em class="icon ni ni-globe"></em>
                        </a>
                    </li>
                    
                    <li class="dropdown user-dropdown" id="admin_dropdown_parent">
                        <a href="{{ route('dashboard.profile.information') }}" class="dropdown-toggle">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-name d-none d-sm-block">{{ translate(Str::headline(Auth::user()->name)) }}</div>
                            </div>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
