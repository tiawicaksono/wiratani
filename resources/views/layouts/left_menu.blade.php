<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <div class="image">
            <img src="{{ URL::asset('public/images/user.png') }}" width="48" height="48" alt="User" />
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">John Doe</div>
            <div class="email">john.doe@example.com</div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">input</i>Sign Out</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #User Info -->
    <!-- Menu -->
    <div class="menu">
        <ul class="list">
            <li class="header">MAIN NAVIGATION</li>
            <li>
                <a href="/dashboard">
                    <i class="material-icons">home</i>
                    <span>Dashboard</span>
                </a>
            </li>
            @foreach ($menu['menu'] as $dataMenu)
            @php
            $arrSubMenu = empty($menu["sub_menu"][$dataMenu->menu_id])?NULL:$menu["sub_menu"][$dataMenu->menu_id]
            @endphp
            <li>
                @if (!empty($arrSubMenu))
                <a href="javascript:void(0);" class="menu-toggle">
                    @else
                    <a href="{{ route($dataMenu->menu_link) }}">
                        @endif
                        <i class="material-icons">{{ $dataMenu->menu_icon }}</i>
                        <span>{{ $dataMenu->menu_label }}</span>
                    </a>
                    @if (!empty($arrSubMenu))
                    <ul class="ml-menu">
                        @foreach ($arrSubMenu as $dataSubMenu)
                        <li>
                            <a href="{{ route($dataSubMenu->menu_link) }}">
                                <i class="material-icons">navigate_next</i>
                                <span>{{ $dataSubMenu->menu_label }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
            </li>
            @endforeach
        </ul>
    </div>
    <!-- #Menu -->
    <!-- Footer -->
    <div class="legal">
        <div class="copyright">
            &copy; 2016 - 2017 <a href="javascript:void(0);">AdminBSB - Material Design</a>.
        </div>
        <div class="version">
            <b>Version: </b> 1.0.5
        </div>
    </div>
    <!-- #Footer -->
</aside>