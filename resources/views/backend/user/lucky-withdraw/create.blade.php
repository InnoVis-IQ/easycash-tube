@extends('layout.master')

@section('title', 'Enter Lucky Withdraw')

@push('styles')
<style>
    .payment-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .payment-card:hover {
        border-color: #007bff;
        box-shadow: 0 4px 15px rgba(0,123,255,0.2);
    }
    
    .payment-card.selected {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    
    .payment-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    
    .easypaisa { color: #00a651; }
    .jazzcash { color: #ff6b35; }
    .bank_transfer { color: #6c757d; }
    
    .wallet-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .wallet-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .wallet-card.bg-primary { border-color: #007bff; }
    .wallet-card.bg-success { border-color: #28a745; }
    .wallet-card.bg-warning { border-color: #ffc107; }
    .wallet-card.bg-info { border-color: #17a2b8; }
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
                            <h2 class="mb-2"><i class="fa-solid fa-ticket me-3"></i>Enter Lucky Withdraw</h2>
                            <p class="mb-0">Deposit Rs. 100 for a chance to win Plan 3 (Rs. 7,000) absolutely FREE!</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h3 class="mb-0">Rs. 100</h3>
                            <small>Entry Fee</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Wallet Balance & Entry Form -->
        <div class="col-lg-8">
            <!-- Wallet Balance Section -->
            <div class="card mb-4">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-wallet me-2"></i>Your Wallet Balance</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="text-center p-3 border rounded bg-primary text-white wallet-card">
                                <h6 class="mb-2"><i class="fa-solid fa-piggy-bank me-2"></i>Main Wallet</h6>
                                <h4 class="mb-0">Rs. {{ number_format($mainWallet->balance, 2) }}</h4>
                                <small class="opacity-75">For Entry Fee</small>
                                    </div>
                                </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center p-3 border rounded bg-success text-white wallet-card">
                                <h6 class="mb-2"><i class="fa-solid fa-chart-line me-2"></i>Earnings</h6>
                                <h4 class="mb-0">Rs. {{ number_format($earnWallet->balance, 2) }}</h4>
                                <small class="opacity-75">Daily Profits</small>
                                    </div>
                                </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center p-3 border rounded bg-info text-white wallet-card">
                                <h6 class="mb-2"><i class="fa-solid fa-wallet me-2"></i>Total</h6>
                                <h4 class="mb-0">Rs. {{ number_format($totalWalletBalance, 2) }}</h4>
                                <small class="opacity-75">All Wallets</small>
                            </div>
                        </div>
                        </div>

                    @if($mainWallet->balance < 100)
                        <div class="alert alert-danger mt-3">
                            <h6 class="mb-2"><i class="fa-solid fa-exclamation-triangle me-2"></i>Insufficient Balance</h6>
                            <p class="mb-2">You need Rs. 100 in your Main Wallet to enter the contest.</p>
                            <p class="mb-2"><strong>Current Main Wallet Balance:</strong> Rs. {{ number_format($mainWallet->balance, 2) }}</p>
                            <p class="mb-2"><strong>Required Amount:</strong> Rs. 100</p>
                            <p class="mb-0"><strong>Shortfall:</strong> Rs. {{ number_format(100 - $mainWallet->balance, 2) }}</p>
                            <hr>
                            <div class="text-center">
                                <a href="{{ route('deposits.index') }}" class="btn btn-success btn-lg">
                                    <i class="fa-solid fa-plus me-2"></i>Deposit Now
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success mt-3">
                            <h6 class="mb-2"><i class="fa-solid fa-check-circle me-2"></i>Sufficient Balance</h6>
                            <p class="mb-2">You have enough funds in your Main Wallet to enter the contest.</p>
                            <p class="mb-0"><strong>Available Balance:</strong> Rs. {{ number_format($mainWallet->balance, 2) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Entry Form -->
            @if($mainWallet->balance >= 100)
                <div class="card">
                    <div class="card-header p-3">
                        <h6 class="mb-0"><i class="fa-solid fa-ticket me-2"></i>Enter Contest</h6>
                    </div>
                    <div class="card-body p-4">
                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="mb-2"><i class="fa-solid fa-exclamation-triangle me-2"></i>Error</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('lucky-withdraw.store') }}" method="POST" id="entryForm">
                            @csrf
                            
                            <!-- Entry Confirmation -->
                            <div class="mb-4">
                                <div class="alert alert-info">
                                    <h6 class="mb-2"><i class="fa-solid fa-info-circle me-2"></i>Entry Confirmation</h6>
                                    <p class="mb-2">You are about to enter the Lucky Withdraw contest with the following details:</p>
                                    <ul class="mb-0">
                                        <li><strong>Entry Fee:</strong> Rs. 100 (deducted from Main Wallet)</li>
                                        <li><strong>Prize:</strong> Plan 3 (Rs. 7,000) absolutely FREE</li>
                                        <li><strong>Duration:</strong> 15 days with Rs. 700 daily profit</li>
                                        <li><strong>Total Potential Profit:</strong> Rs. 10,500</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" value="1" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('lucky-withdraw.index') }}" class="btn btn-secondary me-md-2">
                                    <i class="fa-solid fa-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                        <i class="fa-solid fa-ticket me-2"></i> Enter Contest (Rs. 100)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Contest Information -->
        <div class="col-lg-4">
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

            <div class="card mb-4">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-clock me-2"></i>Current Round</h6>
                </div>
                <div class="card-body p-3">
                    <div class="text-center">
                        <div id="roundCountdown" class="h4 text-primary mb-2"></div>
                        <p class="mb-1"><strong>Round End:</strong></p>
                        <p class="mb-0">{{ \Carbon\Carbon::parse($currentRoundEnd ?? now()->addDays(14))->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-question-circle me-2"></i>Need Help?</h6>
                </div>
                <div class="card-body p-3">
                    <button class="btn btn-outline-primary btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#howItWorksModal">
                        <i class="fa-solid fa-play me-2"></i>How It Works
                    </button>
                    <a href="{{ route('deposits.index') }}" class="btn btn-outline-success btn-sm w-100">
                        <i class="fa-solid fa-plus me-2"></i>Add Funds
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Lucky Withdraw Contest Terms:</h6>
                <ol>
                    <li>Entry fee is Rs. 100 per round (deducted from Main Wallet)</li>
                    <li>Only one entry per user per round</li>
                    <li>Entry fee must be paid from Main Wallet only (personal deposits)</li>
                    <li>❌ Cannot use Bonus Wallet funds</li>
                    <li>❌ Cannot use Earnings Wallet funds</li>
                    <li>Winner selection is random and fair</li>
                    <li>Winner receives Plan 3 (Rs. 7,000) absolutely free</li>
                    <li>Plan is automatically activated upon winning</li>
                    <li>All decisions by admin are final</li>
                    <li>No refunds once entry is submitted</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Modal -->
<div class="modal fade" id="howItWorksModal" tabindex="-1" aria-labelledby="howItWorksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="howItWorksModalLabel">How Lucky Withdraw Works</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Participation</h6>
                        <ol>
                            <li>Ensure you have Rs. 100 in your Main Wallet</li>
                            <li>Click "Enter Contest" to submit your entry</li>
                            <li>Entry fee is automatically deducted from Main Wallet</li>
                            <li>You're immediately entered into the contest!</li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Winning</h6>
                        <ol>
                            <li>Every 15 days, one winner is selected</li>
                            <li>Winner gets Plan 3 absolutely free</li>
                            <li>Plan is activated automatically</li>
                            <li>Start earning Rs. 700 daily profit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Form submission with validation
document.getElementById('entryForm').addEventListener('submit', function(e) {
    const terms = document.getElementById('terms').checked;
    
    if (!terms) {
        e.preventDefault();
        alert('Please accept the terms and conditions.');
        return;
    }
    
    // Confirm entry
    if (!confirm('Are you sure you want to enter the Lucky Withdraw contest? This will deduct Rs. 100 from your Main Wallet.')) {
        e.preventDefault();
        return;
    }
    
    // Disable submit button to prevent double submission
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Entering Contest...';
});

// Update countdown timer
function updateCountdown() {
    const endDate = new Date('{{ $currentRoundEnd ?? now()->addDays(14)->format('Y-m-d') }}').getTime();
    const now = new Date().getTime();
    const distance = endDate - now;
    
    if (distance < 0) {
        document.getElementById('roundCountdown').innerHTML = "Round Ended";
        return;
    }
    
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    
    document.getElementById('roundCountdown').innerHTML = `${days}d ${hours}h`;
}

updateCountdown();
setInterval(updateCountdown, 1000);
</script>
@endpush
