@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
<div class="container mt-4">
    <h2>Wallet & Earnings</h2>
    
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-body text-center">
                <h5>Wallet Balance</h5>
                <h3>{{ $walletBalance }} PKR</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-body text-center">
                <h5>Total Earned</h5>
                <h3>{{ $totalEarnings }} PKR</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-body text-center">
                <h5>Total Withdrawn</h5>
                <h3>{{ $totalWithdrawals }} PKR</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-body text-center">
                <h5>Bonus Earnings</h5>
                <h3>{{ $bonusEarnings }} PKR</h3>
            </div>
        </div>
    </div>

    <!-- Daily Earnings Chart -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Daily Earnings (Last 30 Days)</h5>
        </div>
        <div class="card-body">
            <canvas id="earningsChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Withdrawals</h5>
                </div>
                <div class="card-body">
                    @forelse($withdrawals->take(5) as $withdrawal)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $withdrawal->amount }} PKR</strong>
                                <br>
                                <small class="text-muted">{{ $withdrawal->created_at->format('M d, Y') }}</small>
                            </div>
                            <span class="badge bg-{{ $withdrawal->status == 'transferred' ? 'success' : ($withdrawal->status == 'requested' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted">No withdrawals yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Deposits</h5>
                </div>
                <div class="card-body">
                    @forelse($deposits->take(5) as $deposit)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $deposit->amount }} PKR</strong>
                                <br>
                                <small class="text-muted">{{ $deposit->created_at->format('M d, Y') }}</small>
                            </div>
                            <span class="badge bg-{{ $deposit->status == 'approved' ? 'success' : ($deposit->status == 'deposited' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($deposit->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted">No deposits yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings Breakdown -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Earnings Breakdown</h5>
        </div>
        <div class="card-body">
            @forelse($subscriptions as $subscription)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>{{ $subscription->plan ? $subscription->plan->title : 'Unknown Plan' }}</strong>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted">Daily Profit:</span> {{ $subscription->plan ? $subscription->plan->daily_profit : 0 }} PKR
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted">Total Earned:</span> {{ $subscription->earning }} PKR
                    </div>
                    <div class="col-md-2">
                        <span class="badge bg-{{ $subscription->expire_at > date('Y-m-d') ? 'success' : 'secondary' }}">
                            {{ $subscription->expire_at > date('Y-m-d') ? 'Active' : 'Expired' }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-muted">No subscriptions found.</p>
            @endforelse
        </div>
    </div>
</div>

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
@endpush

@push('custom-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data for chart
    const dates = @json(array_keys($dailyEarnings));
    const earnings = @json(array_values($dailyEarnings));
    
    const ctx = document.getElementById('earningsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Daily Earnings (PKR)',
                data: earnings,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection 