{{-- Aside --}}

@php
    $kt_logo_image = 'Master_sidebar.png';
@endphp

@if (config('layout.brand.self.theme') === 'light')
    @php $kt_logo_image = 'Group20.png' @endphp
@elseif (config('layout.brand.self.theme') === 'dark')
    @php $kt_logo_image = 'Group20.png' @endphp
@endif

<div class="aside aside-left {{ Metronic::printClasses('aside', false) }} d-flex flex-column flex-row-auto"
    id="kt_aside">

    {{-- Brand --}}
    <div class="brand flex-column-auto {{ Metronic::printClasses('brand', false) }}" id="kt_brand">
        <div class="brand-logo text-center">
            <a href="{{ url('/') }}">

                <img class="pt-10  w-70" alt="{{ config('app.name') }}"
                    src="{{ asset('media/logos/' . $kt_logo_image) }}" />
            </a>
        </div>

        @if (config('layout.aside.self.minimize.toggle'))
            <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
                {{ Metronic::getSVG('media/svg/icons/Navigation/Angle-double-left.svg', 'svg-icon-xl') }}
            </button>
        @endif

    </div>

    {{-- Aside menu --}}
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

        {{-- @if (config('layout.aside.self.display') === false)
            <div class="header-logo">
                <a href="{{ url('/') }}">


                    <img alt="{{ config('app.name') }}" src="{{ asset('media/logos/' . $kt_logo_image) }}" />
                </a>
            </div>
        @endif --}}

        @php
        if (auth()->user()->role_id) {
        $role = getRoleById(auth()->user()->role_id);
        $existingPermissions = $role->permission ? json_decode($role->permission, true) : [];
        }
        @endphp

        <div id="kt_aside_menu" class="aside-menu {{ Metronic::printClasses('aside_menu', false) }}"
            data-menu-vertical="1" {{ Metronic::printAttrs('aside_menu') }}>
            <ul class="menu-nav {{ Metronic::printClasses('aside_menu_nav', false) }}">
                @if (isset($existingPermissions['dashboard']) && $existingPermissions['dashboard'] != 0)
                <li class="menu-item menu-item-submenu @yield('dashboard')" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="{{ url('/admin/dashboard') }}" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <span class="flaticon-dashboard"></span>
                        </span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                @endif
                @php
                    // Example menu structure
                    $menu = modulesListNew();
                    $menu = collect($menu)
                        ->map(function ($item) {
                            return [
                                'label' => $item['module_name'],
                                'icon' => $item['icon'] ?? 'dashboard',
                                'route' => $item['route'] ?? '#',
                                'sortOrder' => $item['sortOrder'] ?? 0,
                                'id' => $item['id'] ?? '',
                                'is_left_menu' => $item['is_left_menu'] ?? false,
                                'children' => isset($item['children'])
                                    ? collect($item['children'])
                                        ->map(function ($child) {
                                            return [
                                                'label' => $child['module_name'],
                                                'icon' => $child['icon'] ?? 'dashboard',
                                                'route' => $child['route'] ?? '#',
                                                'sortOrder' => $child['sortOrder'] ?? 0,
                                                'id' => $child['id'] ?? '',
                                            ];
                                        })
                                        ->toArray()
                                    : [],
                            ];
                        })
                        ->toArray();
                    // Sort the menu items by sortOrder
                    $menu = collect($menu)->
                        filter(function ($item) {
                            return isset($item['is_left_menu']) && $item['is_left_menu'] === true;
                        })->sortBy('sortOrder')->values()->toArray();
                @endphp

                @foreach (collect($menu)->sortBy('sortOrder') as $item)
                    @php
                        $hasChildren =
                            isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
                    @endphp
                    @if(isset($existingPermissions[$item['id']]) && $existingPermissions[$item['id']] != 0)
                    <li class="menu-item @yield($item['id']) {{ $hasChildren ? 'menu-item-submenu' : '' }}"
                        aria-haspopup="true" {{ $hasChildren ? 'data-menu-toggle=hover' : '' }}>
                        <a href="{{ $hasChildren ? '#' : url('/admin/' . ltrim($item['route'], '/')) }}"
                            class="menu-link {{ $hasChildren ? 'menu-toggle' : '' }}">
                            <span class="svg-icon menu-icon">
                                <span class="svg-icon menu-icon">
                                        <span class="flaticon-{{ $item['icon'] }}"></span>
                                </span>
                            </span>
                            <span class="menu-text">{{ $item['label'] }}</span>
                            @if ($hasChildren)
                                <i class="menu-arrow"></i>
                            @endif
                        </a>
                        @if ($hasChildren)
                            <div class="menu-submenu" kt-hidden-height="320">
                                <span class="menu-arrow"></span>
                                @if(isset($existingPermissions[$item['id']]) && $existingPermissions[$item['id']] != 0)
                                <ul class="menu-subnav">
                                    <li class="menu-item menu-item-parent" aria-haspopup="true">
                                        <span class="menu-link"><span
                                                class="menu-text">{{ $item['label'] }}</span></span>
                                    </li>
                                    @foreach ($item['children'] as $child)
                                        <li class="menu-item" aria-haspopup="true">
                                            <a href="{{ url('/admin/' . $child['route']) }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-line"><span></span></i>
                                                <span class="menu-text">{{ $child['label'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        @endif
                    </li>
                    @endif
                @endforeach
                @if (isset($existingPermissions['user']) && $existingPermissions['user'] != 0 || isset($existingPermissions['role']) && $existingPermissions['role'] != 0)
                <li class="menu-item menu-item-submenu @yield('user') @yield('role')" aria-haspopup="true"
                    data-menu-toggle="hover">
                    <a href="#" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <span class="flaticon-users"></span>
                        </span>
                        <span class="menu-text">Admin Users</span><i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu " kt-hidden-height="320" style=""><span
                            class="menu-arrow"></span>
                        <ul class="menu-subnav">
                            <li class="menu-item  menu-item-parent" aria-haspopup="true"><span
                                    class="menu-link"><span class="menu-text">Admin</span></span></li>
                            @if (isset($existingPermissions['role']) && $existingPermissions['role'] != 0)
                            <li class="menu-item  @yield('role')" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{ url('/admin/role/list') }}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line"><span></span></i>
                                    <span class="menu-text">Roles</span>
                                </a>
                            </li>
                            @endif

                            @if (isset($existingPermissions['user']) && $existingPermissions['user'] != 0)
                            <li class="menu-item  @yield('user')" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{ url('/admin/user/list') }}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line"><span></span></i>
                                    <span class="menu-text">Users</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif
                {{-- settings --}}
                @if (isset($existingPermissions['settings']) && $existingPermissions['settings'] != 0 || isset($existingPermissions['master']) && $existingPermissions['master'] != 0)
                <li class="menu-item menu-item-submenu @yield('settings')" aria-haspopup="true"
                    data-menu-toggle="hover">
                    <a href="#" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <span class="flaticon-settings"></span>
                        </span>

                        <span class="menu-text">Settings</span><i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu " kt-hidden-height="320" style=""><span
                            class="menu-arrow"></span>
                        <ul class="menu-subnav">
                            <li class="menu-item  menu-item-parent" aria-haspopup="true"><span
                                    class="menu-link"><span class="menu-text">Admin</span></span></li>
                            @if (isset($existingPermissions['master']) && $existingPermissions['master'] != 0)
                            <li class="menu-item  @yield('settings')" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{ url('/admin/settings/master') }}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line"><span></span></i>
                                    <span class="menu-text">Master</span>
                                </a>
                            </li>
                            @endif

                            @if (isset($existingPermissions['settings']) && $existingPermissions['settings'] != 0)
                            <li class="menu-item  @yield('settings')" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{ url('/admin/settings/list') }}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line"><span></span></i>
                                    <span class="menu-text">Basic Information</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                {{-- Dynamic Form --}}
            </ul>
        </div>
    </div>

</div>
