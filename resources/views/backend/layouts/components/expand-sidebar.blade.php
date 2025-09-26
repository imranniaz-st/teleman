<div class="{{ search_side_menu(Route::CurrentRouteName()) == true ? 'nk-sidebar nk-sidebar-mobile' : null }}">
    <div class="{{ search_side_menu(Route::CurrentRouteName()) == true ? 'nk-sidebar-inner' : null }}" data-simplebar>
        <ul class="nk-menu nk-menu-md font-size-16">
            @if (search_side_menu(Route::CurrentRouteName()) == true)
            <li class="nk-menu-heading">
                <h5 class="overline-title text-primary-alt">
                    {{ translate(Str::replace('_', ' ', search_side_menu(Route::CurrentRouteName()))) }}
                </h5>
            </li>
            @endif
            
            @foreach (extended_menu() as $menuKey => $menu)
                @if ($menuKey == search_side_menu(Route::CurrentRouteName()))
                    @can($menu['permission'])
                        <li class="nk-menu-item @if(isset($menu['route_name'])) {{ request()->routeIs($menu['route_name']) ? 'has-sub' : '' }} @endif">
                            <a href="{{ isset($menu['route_name']) ? route($menu['route_name'], $menu['params']) : 'javascript:;' }}" 
                                class="nk-menu-link">
                                <span class="nk-menu-icon">
                                    <em class="icon ni {{ $menu['icon'] }}"></em>
                                </span>
                                <span class="nk-menu-text">{{ $menu['title'] }}</span>
                            </a>
                            @if (isset($menu['sub_menu']))
                                @foreach ($menu['sub_menu'] as $subMenuKey => $subMenu)
                                    @can($subMenu['permission'])
                                        <li class="nk-menu-item">
                                            <a href="{{ isset($subMenu['route_name']) ? route($subMenu['route_name'], $subMenu['params']) : 'javascript:;' }}" 
                                                class="nk-menu-link {{ isset($subMenu['class']) ? $subMenu['class'] : null }}"
                                                target="{{ isset($subMenu['new_window']) ? $subMenu['new_window'] : null }}">
                                                <span class="nk-menu-icon">
                                                    <em class="icon ni {{ $subMenu['icon'] }}"></em>
                                                </span>
                                                <span class="nk-menu-text">
                                                    {{ $subMenu['title'] }} 
                                                    {{-- <sup>inbound</sup> --}}
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                @endforeach
                            @endif
                        </li>
                    @endcan
                @endif
            @endforeach

        </ul>
    </div>
</div>
