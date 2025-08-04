<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row" style="background: linear-gradient(135deg, #28a745 0%, #007bff 100%);">
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between w-100">
        <!-- Left section -->
        <div class="d-flex align-items-center">
        </div>
        
        <!-- Center section -->
        <div class="d-flex align-items-center">
            <span class="text-white" style="color: white !important; font-size: 1.1rem; font-weight: 600;">EasyPay Tube</span>
        </div>
        
        <!-- Right section -->
        <div class="d-flex align-items-center">
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item dropdown d-none d-xl-inline-block">
                    <a class="nav-link dropdown-toggle text-white" id="UserDropdown" href="#" data-toggle="dropdown"
                        aria-expanded="false" style="color: white !important;">
                        <span class="profile-text d-none d-md-inline-flex text-white" style="color: white !important;">{{ Auth::user()['name'] }}</span>
                        <img class="img-xs rounded-circle" src="{{ url('assets/images/faces/face0.png') }}" alt="Profile image">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                        <a class="dropdown-item" href="{{ route('password.change') }}">
                            <i class="dropdown-item-icon mdi mdi-account-key text-primary"></i>
                            Change Password
                        </a>
                        @if(Auth::user()->role == 'USER')
                        <a class="dropdown-item" href="{{ route('support.index') }}">
                            <i class="dropdown-item-icon mdi mdi-help-circle text-primary"></i>
                            Support & Help
                        </a>
                        @else
                        <a class="dropdown-item" href="{{ route('admin.settings') }}">
                            <i class="dropdown-item-icon mdi mdi-settings text-primary"></i>
                            Settings
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="dropdown-item-icon mdi mdi-logout text-primary"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
