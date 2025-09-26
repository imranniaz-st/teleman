<div class="nk-header nk-header-absolute is-light">
                    <div class="container-lg wide-xl">
                        <div class="nk-header-wrap">
                            <div class="nk-header-brand">
                                <a href="{{ route('frontend') }}" class="logo-link">
                                    <img class="logo-light logo-img" src="{{ darkLogo() }}" srcset="{{ darkLogo() }} 2x" alt="{{ appName() }}">
                                    <img class="logo-dark logo-img" src="{{ logo() }}" srcset="{{ logo() }} 2x" alt="{{ appName() }}">
                                </a>
                            </div><!-- .nk-header-brand -->
                            <div class="nk-header-tools">
                                <ul class="nk-quick-nav">

                                    <li class="dropdown notification-dropdown" id="drpParent">
                                        <a href="javascript:;" class=" nk-quick-nav-icon mr-lg-n1" >
                                            <div><em class="icon">{{ curr() }}</em></div>
                                        </a>
                                        <div  class="dropdown-menu dropdown-menu-xl dropdown-menu-right dropdown-menu-s1" id="dropdown_menu_cc">
                                          
                                            <div class="dropdown-body">
                                                <div class="nk-notification">
                                                    @foreach(allCurrencies() as $currency)
                                                    <a href="{{ route('switch.currency') }}" 
                                                        onclick="event.preventDefault();
                                                        document.getElementById('{{$currency->symbol}}').submit();">
                                                        <div class="nk-notification-item dropdown-inner">
                                                            <div class="nk-notification-icon">
                                                                <em class="icon icon-circle bg-info-dim">{{$currency->icon}}</em>
                                                            </div>
                                                            <div class="nk-notification-content">
                                                                <div class="nk-notification-text">{{$currency->name}} ({{$currency->symbol}})</div>
                                                                <div class="nk-notification-time">{{$currency->icon}}{{$currency->amount}}</div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <form id="{{ $currency->symbol }}" class="d-none" action="{{ route('switch.currency') }}"
                                                        method="GET">
                                                        <input type="hidden" name="currency" value="{{ $currency->symbol }}">
                                                    </form>
                                                    @endforeach
                                                </div><!-- .nk-notification -->
                                            </div><!-- .nk-dropdown-body -->
                                        </div>
                                    </li><!-- .dropdown -->

                                    <li class="dropdown user-dropdown" id="admin_dropdown_parent">
                                        <a href="javascript:;" class="dropdown-toggle">
                                            <div class="user-toggle">
                                                <div class="user-avatar sm">
                                                    <em class="icon ni ni-user-alt"></em>
                                                </div>
                                                <div class="user-name dropdown-indicator d-none d-sm-block">{{ Str::headline(Auth::user()->name) }}</div>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1 z-index-99" id="admin_dropdown_child">
                                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                                <div class="user-card">
                                                    <div class="user-avatar">
                                                        <img alt="{{ Auth::user()->name }}" 
                                                        class="rounded-full" 
                                                        src="{{ avatar(Auth::user()->name) }}">
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="lead-text">{{ Auth::user()->name }}</span>
                                                        @can('customer')
                                                            <span class="lead-text">
                                                                {{ translate('Credit: ') }} {{ user_current_credit(Auth::id()) }}
                                                            </span>

                                                            <span class="lead-text">
                                                                <a href="{{ route('frontend.pricing') }}" class="text-primary">{{ translate('Top Up') }}</a>
                                                            </span>
                                                        @endcan
                                                    </div>
                                                    <div class="user-action">
                                                        <a class="btn btn-icon mr-n2" href="{{ route('dashboard.profile.information') }}"><em class="icon ni ni-setting"></em></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-inner">
                                                <ul class="link-list">
                                                    <li><a href="{{ route('dashboard.profile.information') }}"><em class="icon ni ni-setting-alt"></em><span>{{ translate('Account Setting') }}</span></a></li>

                                                    <li class="d-none"><a href="javascript:;"><em class="icon ni ni-activity-alt"></em><span>{{ translate('Login Activity') }}</span></a></li>

                                                    <li><a class="dark-switch"  href="javascript:;" onclick="ChangeMode()"><em class="icon ni ni-moon"></em><span>{{ translate('Dark Mode') }}</span></a></li>
                                                </ul>
                                            </div>
                                            <div class="dropdown-inner">
                                                <ul class="link-list">
                                                    <li>
                                                        <a href="{{ route('logout') }}"
                                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                                     <em class="icon ni ni-signout"></em><span>{{ translate('Sign out') }}</span></a>
                                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                            @csrf
                                                        </form>
                                                            
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li><!-- .dropdown -->
                                    <li class="dropdown notification-dropdown d-none">
                                        <a href="javascript:;" class="dropdown-toggle nk-quick-nav-icon mr-lg-n1" data-toggle="dropdown">
                                            <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right dropdown-menu-s1">
                                            <div class="dropdown-head">
                                                <span class="sub-title nk-dropdown-title">{{ translate('Notifications') }}</span>
                                                <a href="javascript:;">{{ translate('Mark All as Read') }}</a>
                                            </div>
                                            <div class="dropdown-body">
                                                <div class="nk-notification">
                                                    <div class="nk-notification-item dropdown-inner">
                                                        <div class="nk-notification-icon">
                                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                                        </div>
                                                        <div class="nk-notification-content">
                                                            <div class="nk-notification-text">{{ translate('You have requested to') }} <span>{{ translate('withdrawal') }}</span></div>
                                                            <div class="nk-notification-time">{{ translate('2 hrs ago') }}</div>
                                                        </div>
                                                    </div>
                                             
                                                </div><!-- .nk-notification -->
                                            </div><!-- .nk-dropdown-body -->
                                            <div class="dropdown-foot center">
                                                <a href="javascript:;">{{ translate('View All') }}</a>
                                            </div>
                                        </div>
                                    </li><!-- .dropdown -->
                                    <li class="d-lg-none">
                                        <a href="javascript:;" class="toggle nk-quick-nav-icon mr-n1" id='toggle_trigger_nikka'><em class="icon ni ni-menu"></em></a>
                                    </li>
                                </ul><!-- .nk-quick-nav -->
                            </div><!-- .nk-header-tools -->
                        </div><!-- .nk-header-wrap -->
                    </div><!-- .container-fliud -->
                </div>