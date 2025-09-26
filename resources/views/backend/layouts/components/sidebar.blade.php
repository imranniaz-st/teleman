<div class="nk-aside" id="sidebar_nk_aside">
    <div class="nk-sidebar-menu" data-simplebar>
        <ul class="nk-menu">
            @foreach (menu() as $menuKey => $menu)
                @can($menu['permission'])
                    <li class="nk-menu-item
                    @if(isset($menu['route_name']))
                        {{ request()->routeIs($menu['route_name']) ? 'active' : '' }}
                    @endif
                    " id="{{ $menuKey }}">
                        <a href="{{ isset($menu['route_name']) ? route($menu['route_name'], $menu['params']) : 'javascript:;' }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni {{ $menu['icon'] }}"></em></span>
                            <span class="nk-menu-text">{{ $menu['title'] }}</span>
                        </a>
                        @if (isset($menu['sub_menu']))
                            <ul class="nk-menu-sub">
                                @foreach ($menu['sub_menu'] as $subMenuKey => $subMenu)
                                    @if (isset($subMenu['permission']))
                                        @can($subMenu['permission'])
                                            <li class="nk-menu-item 
                                            @if(isset($subMenu['route_name']))
                                                {{ request()->routeIs($subMenu['route_name']) ? 'active' : '' }}
                                            @endif
                                            ">
                                                <a href="{{ isset($subMenu['route_name']) ? route($subMenu['route_name'], $subMenu['params']) : 'javascript:;' }}" class="nk-menu-link">
                                                    <span class="nk-menu-text"><em class="ni ni-{{ $subMenu['icon'] }}"></em>{{ $subMenu['title'] }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endcan
            @endforeach
            </ul>

            <ul class="nk-menu nk-menu-sm">
                <!-- Menu -->
                @forelse(menus('dashboard menu 1') as $menu)
                    <li class="nk-menu-item">
                        <a href="{{ $menu['link'] ?? '#' }}" target="_blank" class="nk-menu-link" data-original-title="FAQs" title="FAQs">
                            <span class="nk-menu-text">{{ $menu['label'] ?? null }}</span>
                        </a>
                    </li>
                @empty

                @endforelse
            </ul>

        </div><!-- .nk-sidebar-menu -->
        <div class="nk-aside-close">
            <a href="javascript:;" class="toggle" data-target="sideNav"><em class="icon ni ni-cross"></em></a>
        </div><!-- .nk-aside-close -->
</div>