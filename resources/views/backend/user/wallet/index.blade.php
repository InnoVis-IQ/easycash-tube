@extends('layout.master')

@section('title', 'My Wallets')

@push('styles')
<style>
    .wallet-card {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .wallet-card:hover {
        transform: translateY(-5px);
    }
    
    .main-wallet {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .earn-wallet {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .wallet-icon {
        font-size: 3rem;
        opacity: 0.8;
    }
    
    .balance-amount {
        font-size: 2.5rem;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .transfer-btn {
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .transfer-btn:hover {
        transform: scale(1.05);
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
                            <h2 class="mb-2"><i class="fa-solid fa-wallet me-3"></i>My Wallets</h2>
                            <p class="mb-0">Manage your funds across different wallet types</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h3 class="mb-0 text-primary">Rs. {{ number_format($totalBalance, 2) }}</h3>
                            <small class="text-muted">Total Balance</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Cards -->
    <div class="row mb-4">
        <!-- Main Wallet -->
        <div class="col-lg-4 col-md-4 mb-4">
            <div class="card wallet-card main-wallet">
                <div class="card-body p-4 text-center">
                    <div class="wallet-icon mb-3">
                        <i class="fa-solid fa-piggy-bank"></i>
                    </div>
                    <h5 class="mb-3">Main Wallet</h5>
                    <div class="balance-amount mb-3">Rs. {{ number_format($mainWallet->balance, 2) }}</div>
                    <p class="mb-3 small">Deposits & Bonuses</p>
                    <div class="row text-center">
                        <div class="col-12">
                            <small>Total Deposited</small>
                            <div class="fw-bold">Rs. {{ number_format($mainWallet->total_deposited, 2) }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark">
                            <i class="fa-solid fa-check me-1"></i>Can Buy Plans
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earn Wallet -->
        <div class="col-lg-4 col-md-4 mb-4">
            <div class="card wallet-card earn-wallet">
                <div class="card-body p-4 text-center">
                    <div class="wallet-icon mb-3">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h5 class="mb-3">Earn Wallet</h5>
                    <div class="balance-amount mb-3">Rs. {{ number_format($earnWallet->balance, 2) }}</div>
                    <p class="mb-3 small">Daily Plan Earnings (Withdrawable)</p>
                    <div class="row text-center">
                        <div class="col-6">
                            <small>Earned</small>
                            <div class="fw-bold">Rs. {{ number_format($earnWallet->total_earned, 2) }}</div>
                        </div>
                        <div class="col-6">
                            <small>Withdrawn</small>
                            <div class="fw-bold">Rs. {{ number_format($earnWallet->total_withdrawn, 2) }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success ms-1">
                            <i class="fa-solid fa-arrow-up me-1"></i>Withdrawable
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Wallet -->
        <div class="col-lg-4 col-md-4 mb-4">
            <div class="card wallet-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body p-4 text-center">
                    <div class="wallet-icon mb-3">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <h5 class="mb-3">Total Balance</h5>
                    <div class="balance-amount mb-3">Rs. {{ number_format($totalBalance, 2) }}</div>
                    <p class="mb-3 small">All Wallets Combined</p>
                    <div class="row text-center">
                        <div class="col-6">
                            <small>Withdrawable</small>
                            <div class="fw-bold">Rs. {{ number_format($withdrawableBalance, 2) }}</div>
                        </div>
                        <div class="col-6">
                            <small>For Plans</small>
                            <div class="fw-bold">Rs. {{ number_format($planPurchaseBalance, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Transfer Funds Section -->
    <div class="row mb-4 d-none">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-exchange-alt me-2"></i>Transfer Funds</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('wallet.transfer') }}" method="POST" id="transferForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">From Wallet</label>
                                <select name="from_wallet" class="form-select" required>
                                    <option value="">Select Source</option>
                                    <option value="main" {{ old('from_wallet') == 'main' ? 'selected' : '' }}>Main Wallet (Rs. {{ number_format($mainWallet->balance, 2) }})</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">To Wallet</label>
                                <select name="to_wallet" class="form-select" required>
                                    <option value="">Select Destination</option>
                                    <option value="earn" {{ old('to_wallet') == 'earn' ? 'selected' : '' }}>Earn Wallet (Rs. {{ number_format($earnWallet->balance, 2) }})</option>
                                    <option value="main" {{ old('to_wallet') == 'main' ? 'selected' : '' }}>Main Wallet (Rs. {{ number_format($mainWallet->balance, 2) }})</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number" name="amount" class="form-control" 
                                           placeholder="0.00" step="0.01" min="0.01" 
                                           value="{{ old('amount') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary transfer-btn">
                                <i class="fa-solid fa-exchange-alt me-2"></i>Transfer Funds
                            </button>
                            <a href="{{ route('wallet.history') }}" class="btn btn-outline-secondary ms-2">
                                <i class="fa-solid fa-history me-2"></i>View History
                            </a>
                        </div>
                    </form>
                </div>
        </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-plus-circle me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('deposits.index') }}" class="btn btn-success">
                            <i class="fa-solid fa-arrow-down me-2"></i>Add Funds
                        </a>
                        @if($withdrawableBalance >= 500)
                            <a href="{{ route('withdraws.index') }}" class="btn btn-warning">
                                <i class="fa-solid fa-arrow-up me-2"></i>Withdraw Funds
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fa-solid fa-arrow-up me-2"></i>Withdraw Funds
                                <small class="d-block">Min: Rs. 500</small>
                            </button>
                        @endif
                        <a href="{{ route('plans.index') }}" class="btn btn-info">
                            <i class="fa-solid fa-shopping-cart me-2"></i>Buy Plans
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh wallet balances every 30 seconds
    setInterval(function() {
        fetch('{{ route("wallet.stats") }}')
            .then(response => response.json())
            .then(data => {
                // Update balance displays
                document.querySelectorAll('.balance-amount').forEach((element, index) => {
                    const balances = [data.main_balance, data.earn_balance];
                    if (balances[index] !== undefined) {
                        element.textContent = 'Rs. ' + parseFloat(balances[index]).toFixed(2);
                    }
                });
                
                // Update total balance
                const totalElement = document.querySelector('.stats-card .h3');
                if (totalElement) {
                    totalElement.textContent = 'Rs. ' + parseFloat(data.total_balance).toFixed(2);
                }
            })
            .catch(error => console.log('Error fetching wallet stats:', error));
    }, 30000);
    
    // Transfer form validation
    document.getElementById('transferForm').addEventListener('submit', function(e) {
        const fromWallet = document.querySelector('select[name="from_wallet"]').value;
        const toWallet = document.querySelector('select[name="to_wallet"]').value;
        const amount = parseFloat(document.querySelector('input[name="amount"]').value);
        
        if (fromWallet === toWallet) {
            e.preventDefault();
            alert('Source and destination wallets cannot be the same.');
            return;
        }
        
        if (amount <= 0) {
            e.preventDefault();
            alert('Amount must be greater than 0.');
            return;
        }
        
        // Check available balance
        const fromWalletBalance = {{ $mainWallet->balance }};
        if (amount > fromWalletBalance) {
            e.preventDefault();
            alert('Insufficient balance in source wallet.');
            return;
        }
    });
});
</script>
@endpush