@extends('layouts.master')

@section('title', 'Wallet History')

@push('styles')
<style>
    .transaction-card {
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
    }
    
    .transaction-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateX(5px);
    }
    
    .deposit-card {
        border-left-color: #28a745;
    }
    
    .withdrawal-card {
        border-left-color: #dc3545;
    }
    
    .transfer-card {
        border-left-color: #ffc107;
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .amount-positive {
        color: #28a745;
        font-weight: bold;
    }
    
    .amount-negative {
        color: #dc3545;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2"><i class="fa-solid fa-history me-3"></i>Wallet History</h2>
                            <p class="mb-0">View your transaction history and wallet activity</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <a href="{{ route('wallet.index') }}" class="btn btn-light">
                                <i class="fa-solid fa-arrow-left me-2"></i>Back to Wallets
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('wallet.history') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select name="wallet_type" class="form-select">
                                    <option value="all" {{ $walletType == 'all' ? 'selected' : '' }}>All Wallets</option>
                                    <option value="main" {{ $walletType == 'main' ? 'selected' : '' }}>Main Wallet</option>
                                    <option value="earn" {{ $walletType == 'earn' ? 'selected' : '' }}>Earn Wallet</option>
                                    <option value="bonus" {{ $walletType == 'bonus' ? 'selected' : '' }}>Bonus Wallet</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="transaction_type" class="form-select">
                                    <option value="all">All Transactions</option>
                                    <option value="deposit">Deposits</option>
                                    <option value="withdrawal">Withdrawals</option>
                                    <option value="transfer">Transfers</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="date_from" class="form-control" placeholder="From Date">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa-solid fa-filter me-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Summary -->
    <div class="row mb-4">
        @foreach($wallets as $wallet)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ ucfirst($wallet->wallet_type) }} Wallet</h6>
                                <p class="mb-0 text-muted small">{{ $wallet->description }}</p>
                            </div>
                            <div class="text-end">
                                <div class="h5 mb-0 text-primary">Rs. {{ number_format($wallet->balance, 2) }}</div>
                                <small class="text-muted">Current Balance</small>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row text-center">
                            <div class="col-4">
                                <small class="text-muted">Deposited</small>
                                <div class="fw-bold">Rs. {{ number_format($wallet->total_deposited, 2) }}</div>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Earned</small>
                                <div class="fw-bold">Rs. {{ number_format($wallet->total_earned, 2) }}</div>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Withdrawn</small>
                                <div class="fw-bold">Rs. {{ number_format($wallet->total_withdrawn, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Transaction History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-list me-2"></i>Transaction History</h6>
                </div>
                <div class="card-body p-0">
                    @if($deposits->count() > 0 || $withdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deposits as $deposit)
                                        <tr class="transaction-card deposit-card">
                                            <td>{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="fa-solid fa-arrow-down me-1"></i>Deposit
                                                </span>
                                            </td>
                                            <td class="amount-positive">+Rs. {{ number_format($deposit->amount, 2) }}</td>
                                            <td>
                                                <span class="status-badge 
                                                    @if($deposit->status == 'pending') status-pending
                                                    @elseif($deposit->status == 'approved') status-approved
                                                    @elseif($deposit->status == 'rejected') status-rejected
                                                    @endif">
                                                    {{ ucfirst($deposit->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $deposit->payment_method ?? 'Bank Transfer' }}</small>
                                                @if($deposit->transaction_id)
                                                    <br><code class="small">{{ $deposit->transaction_id }}</code>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('deposits.show', $deposit) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    @foreach($withdrawals as $withdrawal)
                                        <tr class="transaction-card withdrawal-card">
                                            <td>{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    <i class="fa-solid fa-arrow-up me-1"></i>Withdrawal
                                                </span>
                                            </td>
                                            <td class="amount-negative">-Rs. {{ number_format($withdrawal->amount, 2) }}</td>
                                            <td>
                                                <span class="status-badge 
                                                    @if($withdrawal->status == 'pending') status-pending
                                                    @elseif($withdrawal->status == 'approved') status-approved
                                                    @elseif($withdrawal->status == 'rejected') status-rejected
                                                    @endif">
                                                    {{ ucfirst($withdrawal->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $withdrawal->payment_method ?? 'Bank Transfer' }}</small>
                                                @if($withdrawal->account_number)
                                                    <br><code class="small">{{ $withdrawal->account_number }}</code>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('withdraws.show', $withdrawal) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fa-solid fa-inbox fa-3x mb-3"></i>
                                <p>No transactions found</p>
                                <a href="{{ route('deposits.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus me-2"></i>Make Your First Deposit
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation to transaction cards
    const cards = document.querySelectorAll('.transaction-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
        card.classList.add('animate__animated', 'animate__fadeInUp');
    });
    
    // Auto-refresh every 60 seconds
    setInterval(function() {
        location.reload();
    }, 60000);
});
</script>
@endpush
