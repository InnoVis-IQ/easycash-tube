@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
<div class="container-fluid px-2">
    @if($subscription_status == 'no')
        <!-- Wallet Balance -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card card-body text-center">
                    <h5><i class="fa-solid fa-wallet me-2"></i>Wallet Balance</h5>
                    <h3>0 PKR</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-body text-center">
                    <h5><i class="fa-solid fa-play-circle me-2"></i>Active Plans</h5>
                    <h3>0</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-body text-center">
                    <h5><i class="fa-solid fa-compass-drafting me-2"></i>Available Plans</h5>
                    <h3>{{ $allPlans->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 text-center">
                <a href="{{ route('plans.index') }}" class="btn btn-primary btn-lg" style="border-radius: 8px;">
                    <i class="fa-solid fa-plus-circle me-2"></i>Buy a plan to start earning
                </a>
            </div>
        </div>
    @else
        <!-- Notification Banners -->
        @if($announcements && count($announcements) > 0)
        <div class="row mb-3">
            <div class="col-12">
                @foreach($announcements as $announcement)
                    <div class="alert alert-{{ $announcement->type }} alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid {{ $announcement->type == 'info' ? '#17a2b8' : ($announcement->type == 'warning' ? '#ffc107' : ($announcement->type == 'success' ? '#28a745' : '#dc3545')) }};">
                        <div class="d-flex align-items-start">
                            <div class="me-3 mt-1">
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
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="alert-heading mb-1 fw-bold">{{ $announcement->title }}</h6>
                                        <p class="mb-1">{{ $announcement->message }}</p>
                                        <small class="text-muted">
                                            <i class="fa-solid fa-clock me-1"></i>
                                            Posted on {{ $announcement->created_at->format('M d, Y \a\t g:i A') }}
                                        </small>
                                    </div>
                                    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- User Progress and Level Card -->
        <div class="row mb-3">
            <div class="col-12 col-lg-8 mb-3">
                <div class="card bg-dark text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success rounded-circle p-2 me-3" style="min-width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-user text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                            </div>
                        </div>
                        
                        <!-- Watched Videos Progress -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small">Current Plan Progress</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $watchedVideosProgress }}%" aria-valuenow="{{ $watchedVideosProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">{{ $daysWatched }} days watched</small>
                                <small class="text-muted">5 days required</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Earnings and Activity Metrics -->
            <div class="col-12 col-lg-4">
                <div class="card bg-light">
                    <div class="card-body p-3">
                        <h5 class="text-center mb-3 small">FINANCIAL SUMMARY</h5>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-center">
                                    <i class="fa-solid fa-chart-line fa-lg text-primary mb-1"></i>
                                    <div class="small"><strong>EARNED</strong></div>
                                    <div class="small text-primary">Rs{{ number_format($totalEarned) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <i class="fa-solid fa-gift fa-lg text-warning mb-1"></i>
                                    <div class="small"><strong>TODAY'S REWARD</strong></div>
                                    <div class="small text-warning">Rs{{ number_format($todayReward) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <i class="fa-solid fa-arrow-down fa-lg text-success mb-1"></i>
                                    <div class="small"><strong>DEPOSITS</strong></div>
                                    <div class="small text-success">Rs{{ number_format($totalDeposits) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <i class="fa-solid fa-arrow-up fa-lg text-danger mb-1"></i>
                                    <div class="small"><strong>WITHDRAWALS</strong></div>
                                    <div class="small text-danger">Rs{{ number_format($totalWithdrawals) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-2">
                        
                        <div class="text-center">
                            <h6 class="mb-1">WALLET BALANCE</h6>
                            <h5 class="text-success mb-0">Rs{{ number_format($walletBalance) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Invite & Earn Section -->
        <div class="row mb-3">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body text-center p-3">
                        <h6 class="mb-2">Daily Reward</h6>
                        <div class="mb-2">
                            <i class="fa-solid fa-play-circle fa-2x text-primary"></i>
                        </div>
                        <h6 class="small">Watch Video</h6>
                        <p class="text-muted small mb-2">Complete daily video to earn rewards</p>
                        <a href="{{ route('videos.watch') }}" class="btn btn-primary btn-sm">Watch Now</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Current Plans Section -->
        @if($subscriptions && count($subscriptions) > 0)
        <div class="card mb-3">
            <div class="card-header p-3">
                <h6 class="mb-0">Current Plans</h6>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    @foreach ($subscriptions as $subscription)
                        @php
                            $remainingDays = (strtotime(date('Y-m-d', strtotime($subscription['expire_at']))) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
                        @endphp
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-header bg-primary text-center p-2">
                                    <strong class="small">{{ $subscription['plan']['title'] ?? 'Plan' }}</strong>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row mb-1">
                                        <div class="col-7 small">Daily Profit:</div>
                                        <div class="col-5 text-end small">{{ $subscription['plan']['daily_profit'] ?? 0 }} PKR</div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-7 small">Profit Earned:</div>
                                        <div class="col-5 text-end small">{{ $subscription['earning'] }} PKR</div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-7 small">Ending in:</div>
                                        <div class="col-5 text-end small">{{ $remainingDays }} days</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-7 small">Total Profit:</div>
                                        <div class="col-5 text-end small">{{ $subscription['plan']['total_profit'] ?? 0 }} PKR</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    @endif
</div>

<script>
function copyReferralLink() {
    const linkInput = document.querySelector('input[readonly]');
    linkInput.select();
    document.execCommand('copy');
    
    // Show feedback
    const copyBtn = event.target;
    const originalText = copyBtn.textContent;
    copyBtn.textContent = 'COPIED!';
    copyBtn.classList.add('btn-success');
    copyBtn.classList.remove('btn-light');
    
    setTimeout(() => {
        copyBtn.textContent = originalText;
        copyBtn.classList.remove('btn-success');
        copyBtn.classList.add('btn-light');
    }, 2000);
}

// Auto-dismiss notifications after 10 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert && alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 10000); // 10 seconds
    });
});
</script>

<style>
.alert {
    border-radius: 8px;
    margin-bottom: 1rem;
}

.alert-dismissible .btn-close {
    padding: 0.75rem 1rem;
}

.alert-heading {
    font-size: 1.1rem;
    font-weight: 600;
}

.alert p {
    font-size: 0.95rem;
    line-height: 1.4;
}

.alert small {
    font-size: 0.8rem;
}
</style>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/dashboard.js') !!}
@endpush
