@extends('layout.master')

@section('title', 'Wallet Management')

@push('styles')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .wallet-type-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .type-main {
        background-color: #e3f2fd;
        color: #1976d2;
    }
    
    .type-earn {
        background-color: #f3e5f5;
        color: #7b1fa2;
    }
    
    .type-bonus {
        background-color: #e8f5e8;
        color: #388e3c;
    }
    
    .balance-positive {
        color: #28a745;
        font-weight: bold;
    }
    
    .balance-zero {
        color: #6c757d;
    }
    
    .adjust-btn {
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="stats-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2"><i class="fa-solid fa-wallet me-3"></i>Wallet Management</h2>
                            <p class="mb-0">Manage user wallets and balances</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="mb-0">{{ $stats['total_wallets'] }}</h4>
                            <small>Total Wallets</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body p-3 text-center">
                    <h5 class="mb-1">Rs. {{ number_format($stats['total_balance'], 2) }}</h5>
                    <small>Total Balance</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body p-3 text-center">
                    <h5 class="mb-1">{{ $stats['main_wallets'] }}</h5>
                    <small>Main Wallets</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body p-3 text-center">
                    <h5 class="mb-1">{{ $stats['earn_wallets'] }}</h5>
                    <small>Earn Wallets</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body p-3 text-center">
                    <h5 class="mb-1">{{ $stats['bonus_wallets'] }}</h5>
                    <small>Bonus Wallets</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.wallets.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select name="wallet_type" class="form-select">
                                    <option value="">All Wallet Types</option>
                                    <option value="main" {{ request('wallet_type') == 'main' ? 'selected' : '' }}>Main Wallets</option>
                                    <option value="earn" {{ request('wallet_type') == 'earn' ? 'selected' : '' }}>Earn Wallets</option>
                                    <option value="bonus" {{ request('wallet_type') == 'bonus' ? 'selected' : '' }}>Bonus Wallets</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Search by user name or email">
                            </div>
                            <div class="col-md-3">
                                <select name="balance_filter" class="form-select">
                                    <option value="">All Balances</option>
                                    <option value="positive" {{ request('balance_filter') == 'positive' ? 'selected' : '' }}>Positive Balance</option>
                                    <option value="zero" {{ request('balance_filter') == 'zero' ? 'selected' : '' }}>Zero Balance</option>
                                    <option value="high" {{ request('balance_filter') == 'high' ? 'selected' : '' }}>High Balance (>Rs. 10,000)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa-solid fa-search me-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallets Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-list me-2"></i>User Wallets</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Wallet Type</th>
                                    <th>Balance</th>
                                    <th>Total Deposited</th>
                                    <th>Total Earned</th>
                                    <th>Total Withdrawn</th>
                                    <th>Permissions</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wallets as $wallet)
                                    <tr>
                                        <td>
                                            <div>
                                                <div class="fw-bold">{{ $wallet->user->name }}</div>
                                                <small class="text-muted">{{ $wallet->user->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="wallet-type-badge type-{{ $wallet->wallet_type }}">
                                                {{ ucfirst($wallet->wallet_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="{{ $wallet->balance > 0 ? 'balance-positive' : 'balance-zero' }}">
                                                Rs. {{ number_format($wallet->balance, 2) }}
                                            </span>
                                        </td>
                                        <td>Rs. {{ number_format($wallet->total_deposited, 2) }}</td>
                                        <td>Rs. {{ number_format($wallet->total_earned, 2) }}</td>
                                        <td>Rs. {{ number_format($wallet->total_withdrawn, 2) }}</td>
                                        <td>
                                            <div class="small">
                                                @if($wallet->is_withdrawable)
                                                    <span class="badge bg-success me-1">Withdrawable</span>
                                                @endif
                                                @if($wallet->can_buy_plans)
                                                    <span class="badge bg-info">Can Buy Plans</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ $wallet->updated_at->format('M d, Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" data-bs-target="#adjustModal{{ $wallet->id }}">
                                                    <i class="fa-solid fa-edit"></i>
                                                </button>
                                                <a href="{{ route('admin.users.show', $wallet->user_id) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fa-solid fa-user"></i>
                                                </a>
                                            </div>

                                            <!-- Adjust Balance Modal -->
                                            <div class="modal fade" id="adjustModal{{ $wallet->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Adjust Wallet Balance</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('admin.wallets.adjust', $wallet) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">User</label>
                                                                    <input type="text" class="form-control" 
                                                                           value="{{ $wallet->user->name }}" readonly>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Wallet Type</label>
                                                                    <input type="text" class="form-control" 
                                                                           value="{{ ucfirst($wallet->wallet_type) }}" readonly>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Current Balance</label>
                                                                    <input type="text" class="form-control" 
                                                                           value="Rs. {{ number_format($wallet->balance, 2) }}" readonly>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Adjustment Type</label>
                                                                    <select name="type" class="form-select" required>
                                                                        <option value="add">Add Funds</option>
                                                                        <option value="deduct">Deduct Funds</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Amount</label>
                                                                    <input type="number" name="amount" class="form-control" 
                                                                           step="0.01" min="0.01" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Reason</label>
                                                                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Adjust Balance</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fa-solid fa-inbox fa-3x mb-3"></i>
                                                <p>No wallets found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($wallets->hasPages())
                    <div class="card-footer p-3">
                        {{ $wallets->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for balance adjustments
    document.querySelectorAll('form[action*="adjust"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const type = this.querySelector('select[name="type"]').value;
            const amount = parseFloat(this.querySelector('input[name="amount"]').value);
            const reason = this.querySelector('textarea[name="reason"]').value;
            
            if (!reason.trim()) {
                e.preventDefault();
                alert('Please provide a reason for the adjustment.');
                return;
            }
            
            const action = type === 'add' ? 'add' : 'deduct';
            const confirmMessage = `Are you sure you want to ${action} Rs. ${amount.toFixed(2)} from this wallet?\n\nReason: ${reason}`;
            
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-refresh every 2 minutes
    setInterval(function() {
        location.reload();
    }, 120000);
});
</script>
@endpush
