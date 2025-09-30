@extends('layouts.master')

@section('title', 'Entry Details')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2"><i class="fa-solid fa-ticket me-3"></i>Entry Details</h2>
                            <p class="mb-0">Lucky Withdraw Entry Information</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h3 class="mb-0">{{ $luckyWithdrawEntry->entry_code }}</h3>
                            <small>Entry Code</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Entry Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>Entry Information</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Entry Code</label>
                                <div class="fw-bold"><code>{{ $luckyWithdrawEntry->entry_code }}</code></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Entry Amount</label>
                                <div class="fw-bold text-success">Rs. {{ number_format($luckyWithdrawEntry->entry_amount) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Payment Method</label>
                                <div class="fw-bold">{{ ucfirst(str_replace('_', ' ', $luckyWithdrawEntry->payment_method)) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Transaction ID</label>
                                <div class="fw-bold">{{ $luckyWithdrawEntry->transaction_id }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Round Period</label>
                                <div class="fw-bold">
                                    {{ \Carbon\Carbon::parse($luckyWithdrawEntry->round_start_date)->format('M d, Y') }} - 
                                    {{ \Carbon\Carbon::parse($luckyWithdrawEntry->round_end_date)->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Submitted Date</label>
                                <div class="fw-bold">{{ $luckyWithdrawEntry->created_at->format('M d, Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Information -->
            <div class="card mt-4">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-flag me-2"></i>Status Information</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Current Status</label>
                                <div>
                                    @if($luckyWithdrawEntry->is_winner)
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                            <i class="fa-solid fa-crown me-2"></i>WINNER!
                                        </span>
                                    @else
                                        <span class="badge 
                                            @if($luckyWithdrawEntry->status == 'pending') bg-warning
                                            @elseif($luckyWithdrawEntry->status == 'approved') bg-success
                                            @elseif($luckyWithdrawEntry->status == 'rejected') bg-danger
                                            @endif fs-6 px-3 py-2">
                                            {{ ucfirst($luckyWithdrawEntry->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Round Status</label>
                                <div>
                                    <span class="badge 
                                        @if($luckyWithdrawEntry->round_status == 'active') bg-info
                                        @elseif($luckyWithdrawEntry->round_status == 'completed') bg-secondary
                                        @elseif($luckyWithdrawEntry->round_status == 'winner_selected') bg-success
                                        @endif fs-6 px-3 py-2">
                                        {{ ucfirst(str_replace('_', ' ', $luckyWithdrawEntry->round_status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($luckyWithdrawEntry->admin_notes)
                        <div class="alert alert-info">
                            <h6><i class="fa-solid fa-sticky-note me-2"></i>Admin Notes</h6>
                            <p class="mb-0">{{ $luckyWithdrawEntry->admin_notes }}</p>
                        </div>
                    @endif

                    @if($luckyWithdrawEntry->is_winner)
                        <div class="alert alert-success">
                            <h5><i class="fa-solid fa-trophy me-2"></i>Congratulations! You Won!</h5>
                            <p class="mb-2">You have won Plan 3 (Rs. 7,000) absolutely free!</p>
                            <p class="mb-0">The plan has been automatically activated in your account. You can start earning Rs. 700 daily profit immediately.</p>
                        </div>
                    @elseif($luckyWithdrawEntry->status == 'approved')
                        <div class="alert alert-success">
                            <h6><i class="fa-solid fa-check-circle me-2"></i>Entry Approved</h6>
                            <p class="mb-0">Your entry has been approved and you are now participating in the contest. Good luck!</p>
                        </div>
                    @elseif($luckyWithdrawEntry->status == 'pending')
                        <div class="alert alert-warning">
                            <h6><i class="fa-solid fa-clock me-2"></i>Pending Approval</h6>
                            <p class="mb-0">Your entry is pending admin approval. Please wait for the admin to review your submission.</p>
                        </div>
                    @elseif($luckyWithdrawEntry->status == 'rejected')
                        <div class="alert alert-danger">
                            <h6><i class="fa-solid fa-times-circle me-2"></i>Entry Rejected</h6>
                            <p class="mb-0">Your entry has been rejected. Please contact support for more information.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Prize Information -->
            <div class="card mb-4">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-gift me-2"></i>Prize Details</h6>
                </div>
                <div class="card-body p-3">
                    <div class="text-center mb-3">
                        <h4 class="text-success">Plan 3</h4>
                        <h3 class="text-primary">Rs. 7,000</h3>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-primary">Duration</h6>
                            <p class="mb-0">15 Days</p>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success">Daily Profit</h6>
                            <p class="mb-0">Rs. 700</p>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h6 class="text-warning">Total Profit</h6>
                        <h4 class="text-success">Rs. 10,500</h4>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="card mb-4">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-user me-2"></i>Participant Info</h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-2">
                        <label class="form-label text-muted small">Name</label>
                        <div class="fw-bold">{{ $luckyWithdrawEntry->user->name }}</div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-muted small">Email</label>
                        <div class="fw-bold">{{ $luckyWithdrawEntry->user->email }}</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted small">Member Since</label>
                        <div class="fw-bold">{{ $luckyWithdrawEntry->user->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('lucky-withdraw.index') }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-arrow-left me-2"></i>Back to Lucky Withdraw
                        </a>
                        @if($luckyWithdrawEntry->is_winner)
                            <a href="{{ route('dashboard') }}" class="btn btn-success">
                                <i class="fa-solid fa-chart-line me-2"></i>View Dashboard
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
