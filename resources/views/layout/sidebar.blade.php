<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar" style="background: linear-gradient(180deg, #28a745 0%, #007bff 100%);">
    <ul class="nav">
        <li class="nav-item nav-profile not-navigation-link">
            <div class="nav-link">
                <div class="user-wrapper mb-0">
                    <div class="profile-image">
                        <img src="{{ url('assets/images/faces/face0.png') }}" alt="profile image">
                    </div>
                    <div class="text-wrapper">
                        <p class="profile-name text-white" style="color: white !important;">{{ Auth::user()['name'] }}</p>
                        <div class="dropdown" data-display="static">
                            <a href="#" class="nav-link d-flex user-switch-dropdown-toggler"
                                id="UsersettingsDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                                <small class="designation text-white" style="color: white !important;">
                                    {{ ucfirst(strtolower(Auth::user()['role'])) }}
                                </small>
                                <span class="status-indicator online"></span>
                            </a>
                            {{-- <div class="dropdown-menu" aria-labelledby="UsersettingsDropdown">
                                <a class="dropdown-item p-0">
                                    <div class="d-flex border-bottom">
                                        <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                            <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>
                                        </div>
                                        <div
                                            class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">
                                            <i class="mdi mdi-account-outline mr-0 text-gray"></i>
                                        </div>
                                        <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                            <i class="mdi mdi-alarm-check mr-0 text-gray"></i>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item mt-2"> Manage Accounts </a>
                                <a class="dropdown-item"> Change Password </a>
                                <a class="dropdown-item"> Check Inbox </a>
                                <a class="dropdown-item"> Sign Out </a>
                            </div> --}}
                        </div>
                    </div>
                </div>

                {{-- <div class="user-wrapper mb-0 mt-2">
                    <small class="designation text-muted">
                        Refferal Code: {{ strtolower(Auth::user()['referral_code']) }}
                    </small>
                </div> --}}
                {{-- <button class="btn btn-success btn-block">New Project <i class="mdi mdi-plus"></i></button> --}}
            </div>
        </li>
        @if (in_array(strtolower(Auth::user()['role']), ['user']))
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center mb-2" style="font-weight: bold; color: white;">
                    Account Balance:
                    @php
                        $userAccount = App\Models\UserAccount::where('user_id', Auth::user()['id'])->first();
                        echo $userAccount['net_amount'] . ' PKR';
                    @endphp
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 offset-md-1">
                    <a href="{{ route('deposits.index') }}" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Deposit</a>
                </div>
                <div class="col-md-4 offset-md-1">
                    <a href="{{ route('withdraws.index') }}" class="btn btn-warning" style="background-color: #ffc107; border-color: #ffc107; color: #212529;">Withdraw</a>
                </div>
            </div>
            {{-- <div class="row mt-3">
                <div class="col-md-10 offset-md-1 text-center">
                    <a data-toggle="modal" data-target="#referModal" class="btn btn-success text-white">Refer a
                        friend</a>
                </div>
            </div> --}}
        @endif
        <li class="nav-item active" style="transition: all 0.3s ease;">
            <a class="nav-link text-white" href="{{ route('dashboard') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                <i class="fa-solid fa-gauge-high"></i>
                <span class="menu-title pl-2">Dashboard</span>
            </a>
        </li>
        @if (in_array(strtolower(Auth::user()['role']), ['superadmin', 'admin']))
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('users.index') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-users"></i>
                    <span class="menu-title pl-2">Users</span>
                </a>
            </li>
        @endif
        @if (in_array(strtolower(Auth::user()['role']), ['superadmin', 'admin']))
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('plans.index') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-compass-drafting"></i>
                    <span class="menu-title pl-2">Plans</span>
                </a>
            </li>
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('videos.index') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-video"></i>
                    <span class="menu-title pl-2">Videos</span>
                </a>
            </li>
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('deposits.index') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-coins"></i>
                    <span class="menu-title pl-2">Deposit Requests</span>
                </a>
            </li>
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('withdraws.index') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-coins"></i>
                    <span class="menu-title pl-2">Withdraw Requests</span>
                </a>
            </li>
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('admin.settings') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-cog"></i>
                    <span class="menu-title pl-2">Settings</span>
                </a>
            </li>
        @endif
        @if (in_array(strtolower(Auth::user()['role']), ['user']))
            <!-- Watch & Earn -->
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('videos.watch') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-play-circle"></i>
                    <span class="menu-title pl-2">Watch & Earn</span>
                </a>
            </li>
            
            <!-- Wallet & Earnings -->
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('wallet.index') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-wallet"></i>
                    <span class="menu-title pl-2">Wallet & Earnings</span>
                </a>
            </li>
            
            <!-- Investment Plans -->
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('plans.index') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-compass-drafting"></i>
                    <span class="menu-title pl-2">Investment Plans</span>
                </a>
            </li>
            
            <!-- Deposits -->
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('deposits.index') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-arrow-down"></i>
                    <span class="menu-title pl-2">Deposits</span>
                </a>
            </li>
            
            <!-- Withdrawals -->
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('withdraws.index') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-arrow-up"></i>
                    <span class="menu-title pl-2">Withdrawals</span>
                </a>
            </li>
            
            <!-- Support & Help -->
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('support.index') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-headset"></i>
                    <span class="menu-title pl-2">Support & Help</span>
                </a>
            </li>
            
            <!-- Account Settings -->
            <li class="nav-item active" style="transition: all 0.3s ease;">
                <a class="nav-link text-white" href="{{ route('profile.edit') }}" style="color: white !important; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fa-solid fa-user-cog"></i>
                    <span class="menu-title pl-2">Account Settings</span>
                </a>
            </li>
        @endif
    </ul>
</nav>


<!-- Modal -->
<div class="modal fade" id="referModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="mytext">{{ route('register') }}?ref={{ Auth::user()['referral_code'] }}</div>
                <div class="text-right">
                    Copy the above referral link and share with your friends to get 15% bonus on first subscription.
                    {{-- <button onclick="copyTextFromElement('mytext')" class="btn btn-default border border-secondary">Copy</button> --}}
                </div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> --}}
        </div>
    </div>
</div>

<script>
    function copyTextFromElement(elementID) {
        let element = document.getElementById(elementID); //select the element
        let elementText = element.textContent; //get the text content from the element
        copyText(elementText); //use the copyText function below
        alert("Referral link copied");
    }

    function copyText(text) {
        navigator.clipboard.writeText(text);
    }
</script>

<style>
/* Override hover styles for sidebar nav items with maximum specificity */
.sidebar .nav .nav-item:not(.nav-profile):hover > .nav-link {
    background: rgba(255, 255, 255, 0.1) !important;
    color: white !important;
}

.sidebar .nav .nav-item:not(.nav-profile):hover > .nav-link i,
.sidebar .nav .nav-item:not(.nav-profile):hover > .nav-link .menu-title {
    color: white !important;
}

/* Ensure active state doesn't override hover */
.sidebar .nav .nav-item.active:not(.nav-profile):hover > .nav-link {
    background: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

.sidebar .nav .nav-item.active:not(.nav-profile):hover > .nav-link i,
.sidebar .nav .nav-item.active:not(.nav-profile):hover > .nav-link .menu-title {
    color: white !important;
}

/* Override the default hover color rule */
.sidebar .nav .nav-item .nav-link:hover {
    color: white !important;
}

/* Override the sidebar-dark hover rule */
.sidebar-dark .sidebar .nav .nav-item .nav-link:hover {
    color: white !important;
}
</style>
