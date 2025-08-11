@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
<div class="container-fluid px-2">
    @if(Session::has('message'))
        <div class="alert alert-success">
            <i class="fa-solid fa-check-circle me-2"></i>
            {{ Session::get('message') }}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
            <i class="fa-solid fa-exclamation-triangle me-2"></i>
            {{ Session::get('error') }}
        </div>
    @endif

    <!-- Wallet Balance -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-body text-center">
                <h5><i class="fa-solid fa-wallet me-2"></i>Wallet Balance</h5>
                <h3>{{ $walletBalance }} PKR</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body text-center">
                <h5><i class="fa-solid fa-play-circle me-2"></i>Active Plans</h5>
                <h3>{{ $userSubscriptions->count() }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body text-center">
                <h5><i class="fa-solid fa-compass-drafting me-2"></i>Available Plans</h5>
                <h3>{{ $allPlans->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Available Plans -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 style="font-weight: bold;"><i class="fa-solid fa-compass-drafting me-2"></i>Available Investment Plans</h4>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($allPlans as $plan)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 {{ $plan->is_purchased ? 'border-success' : '' }}">
                            <div class="card-header text-center bg-success text-white">
                                @if($plan->description)
                                <span class="badge bg-danger me-1 text-white text-start" style="font-size: 0.8em;">
                                    <i class="fa-solid fa-fire text-white me-1"></i> {{ $plan->description }}
                                </span>
                                @endif
                                <h5 class="mb-0">{{ $plan->title }}</h5>
                                @if($plan->is_purchased)
                                    <span class="badge bg-warning text-black">Already Purchased</span>
                                @elseif($plan->can_upgrade)
                                    <span class="badge bg-warning text-black">Available</span>
                                @else
                                    <span class="badge bg-secondary text-black">Insufficient Balance</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">Price:</div>
                                    <div class="col-6 text-end">{{ $plan->amount }} PKR</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">Daily Return:</div>
                                    <div class="col-6 text-end">{{ $plan->daily_profit }} PKR</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">Total Return:</div>
                                    <div class="col-6 text-end">{{ $plan->total_profit }} PKR</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">Duration:</div>
                                    <div class="col-6 text-end">{{ $plan->plan_days }} days</div>
                                </div>
                                @if($plan->description)
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <small class="text-muted">{{ $plan->description }}</small>
                                        </div>
                                    </div>
                                @endif
                                <div class="text-center">
                                    @if($plan->is_purchased)
                                        <button class="btn btn-success" disabled>
                                            <i class="fas fa-check"></i> Purchased
                                        </button>
                                    @elseif($plan->can_upgrade)
                                        <form action="{{ route('userSubscriptions.store') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user" value="{{ Auth::user()['id'] }}">
                                            <button class="btn btn-primary" name="invest" value="{{ $plan->id }}">
                                                <i class="fas fa-plus"></i> Subscribe Now
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-lock"></i> Insufficient Balance
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- My Active Subscriptions -->
    @if($userSubscriptions->count() > 0)
        <div class="card">
            <div class="card-header">
                <h4 style="font-weight: bold;"><i class="fa-solid fa-play-circle me-2"></i>My Active Subscriptions</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Profit Earned</th>
                                <th>Duration</th>
                                <th>Subscription Date</th>
                                <th>Expiry Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userSubscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->plan->title }}</td>
                                    <td>{{ $subscription->plan->amount }} PKR</td>
                                    <td>{{ $subscription->earning }} PKR</td>
                                    <td>{{ $subscription->plan->plan_days }} days</td>
                                    <td>{{ $subscription->created_at->format('M d, Y') }}</td>
                                    <td>{{ $subscription->expire_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('videos.watch') }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-play"></i> Watch Videos
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>



<style>
/* Mobile specific styles */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    

    
    .card-header {
        padding: 0.75rem;
    }
}

@media (max-width: 576px) {
    .col-12 {
        margin-bottom: 0.5rem;
    }
    
    .table-responsive {
        font-size: 0.75rem;
    }
    
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
    }
}
</style>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
