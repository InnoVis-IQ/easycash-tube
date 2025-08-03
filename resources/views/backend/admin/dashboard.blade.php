@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <h5>Admin Dashboard</h5>
        </div>
    </div>
    @if (Session::has('message'))
        <div class="alert alert-info">
            {{ Session::get('message') }}
        </div>
    @endif
    
    <!-- Announcements Section -->
    @if($announcements && count($announcements) > 0)
    <div class="row mb-3">
        <div class="col-12">
            @foreach($announcements as $announcement)
                <div class="alert alert-{{ $announcement->type }} alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            @if($announcement->type == 'info')
                                <i class="fa-solid fa-info-circle fa-lg text-info"></i>
                            @elseif($announcement->type == 'warning')
                                <i class="fa-solid fa-exclamation-triangle fa-lg text-warning"></i>
                            @elseif($announcement->type == 'success')
                                <i class="fa-solid fa-check-circle fa-lg text-success"></i>
                            @elseif($announcement->type == 'danger')
                                <i class="fa-solid fa-times-circle fa-lg text-danger"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-1">{{ $announcement->title }}</h6>
                            <p class="mb-0">{{ $announcement->message }}</p>
                            <small class="text-muted">Posted on {{ $announcement->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Main Statistics Cards -->
    <div class="row">
        <div class="col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <a href="{{ route('users.index') }}">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="fa-solid fa-users fa-2xl" style="color: #28a745;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Total Users</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $totalUsers }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            Active: {{ $totalActiveUsers }}
                        </p>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <a href="{{ route('deposits.index') }}">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="fa-solid fa-hand-holding-dollar fa-2xl" style="color: #007bff;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Pending Deposits</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $pendingDeposits }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            Awaiting approval
                        </p>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <a href="{{ route('withdraws.index') }}">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="fa-solid fa-money-bill-transfer fa-2xl" style="color: #dc3545;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Pending Withdrawals</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $pendingWithdrawals }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            Total: {{ $totalWithdrawals }} | Completed: {{ $completedWithdrawals }}
                        </p>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-money-check-dollar fa-2xl" style="color: #28a745;"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Active Subscriptions</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $activeSubscriptions }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        Total Invested: ₨{{ number_format($totalInvestedAmount) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings and Financial Summary -->
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Financial Summary</h4>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h5 style="color: #007bff;">₨{{ number_format($totalInvestedAmount) }}</h5>
                                <p class="text-muted">Total Invested</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h5 style="color: #28a745;">₨{{ number_format($totalEarnings) }}</h5>
                                <p class="text-muted">Total Earnings</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Plan Distribution</h4>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Active Users</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($planBreakdown as $plan)
                                <tr>
                                    <td>{{ $plan['plan_name'] }}</td>
                                    <td><span class="badge" style="background-color: #28a745;">{{ $plan['active_count'] }}</span></td>
                                    <td>₨{{ number_format($plan['amount']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Recent Users</h4>
                    <div class="list-wrapper">
                        @foreach($recentUsers as $user)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Recent Withdrawals</h4>
                    <div class="list-wrapper">
                        @foreach($recentWithdrawals as $withdrawal)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ $withdrawal->user->name ?? 'N/A' }}</h6>
                                <small class="text-muted">₨{{ number_format($withdrawal->amount) }}</small>
                            </div>
                            <span class="badge badge-{{ $withdrawal->status == 'requested' ? 'warning' : ($withdrawal->status == 'transferred' ? 'success' : 'secondary') }}" 
                                  style="background-color: {{ $withdrawal->status == 'requested' ? '#ffc107' : ($withdrawal->status == 'transferred' ? '#28a745' : '#6c757d') }};">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Recent Deposits</h4>
                    <div class="list-wrapper">
                        @foreach($recentDeposits as $deposit)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ $deposit->user->name ?? 'N/A' }}</h6>
                                <small class="text-muted">₨{{ number_format($deposit->amount) }}</small>
                            </div>
                            <span class="badge badge-{{ $deposit->status == 'deposited' ? 'warning' : ($deposit->status == 'completed' ? 'success' : 'secondary') }}"
                                  style="background-color: {{ $deposit->status == 'deposited' ? '#ffc107' : ($deposit->status == 'completed' ? '#28a745' : '#6c757d') }};">
                                {{ ucfirst($deposit->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/dashboard.js') !!}
@endpush
