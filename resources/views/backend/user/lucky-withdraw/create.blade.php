@extends('layouts.master')

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
        <!-- Entry Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-credit-card me-2"></i>Payment Details</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('lucky-withdraw.store') }}" method="POST" id="entryForm">
                        @csrf
                        
                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <label class="form-label"><strong>Select Payment Method</strong></label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="payment-card p-3 text-center" onclick="selectPaymentMethod('easypaisa')">
                                        <div class="payment-icon easypaisa">
                                            <i class="fa-solid fa-mobile-screen"></i>
                                        </div>
                                        <h6 class="mb-2">EasyPaisa</h6>
                                        <p class="small text-muted mb-0">Mobile Wallet</p>
                                        <input type="radio" name="payment_method" value="easypaisa" class="d-none" id="easypaisa">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-card p-3 text-center" onclick="selectPaymentMethod('jazzcash')">
                                        <div class="payment-icon jazzcash">
                                            <i class="fa-solid fa-mobile-screen"></i>
                                        </div>
                                        <h6 class="mb-2">JazzCash</h6>
                                        <p class="small text-muted mb-0">Mobile Wallet</p>
                                        <input type="radio" name="payment_method" value="jazzcash" class="d-none" id="jazzcash">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-card p-3 text-center" onclick="selectPaymentMethod('bank_transfer')">
                                        <div class="payment-icon bank_transfer">
                                            <i class="fa-solid fa-university"></i>
                                        </div>
                                        <h6 class="mb-2">Bank Transfer</h6>
                                        <p class="small text-muted mb-0">Direct Transfer</p>
                                        <input type="radio" name="payment_method" value="bank_transfer" class="d-none" id="bank_transfer">
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Transaction ID -->
                        <div class="mb-4">
                            <label for="transaction_id" class="form-label"><strong>Transaction ID</strong></label>
                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror" 
                                   id="transaction_id" name="transaction_id" 
                                   placeholder="Enter your transaction ID" 
                                   value="{{ old('transaction_id') }}" required>
                            @error('transaction_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Please enter the transaction ID you received after making the payment.
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('lucky-withdraw.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fa-solid fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fa-solid fa-paper-plane me-2"></i>Submit Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                    <button class="btn btn-outline-info btn-sm w-100" data-bs-toggle="modal" data-bs-target="#paymentInfoModal">
                        <i class="fa-solid fa-credit-card me-2"></i>Payment Info
                    </button>
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
                    <li>Entry fee is Rs. 100 per round</li>
                    <li>Only one entry per user per round</li>
                    <li>Entry fee must be paid from personal funds (not from earnings/bonus)</li>
                    <li>Winner selection is random and fair</li>
                    <li>Winner receives Plan 3 (Rs. 7,000) absolutely free</li>
                    <li>Plan is automatically activated upon winning</li>
                    <li>All decisions by admin are final</li>
                    <li>Refunds are not provided for rejected entries</li>
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
                            <li>Deposit Rs. 100 via your preferred method</li>
                            <li>Submit your entry with transaction ID</li>
                            <li>Wait for admin approval</li>
                            <li>Once approved, you're in the contest!</li>
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

<!-- Payment Information Modal -->
<div class="modal fade" id="paymentInfoModal" tabindex="-1" aria-labelledby="paymentInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentInfoModalLabel">Payment Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h6 class="text-success">EasyPaisa</h6>
                            <p class="small">Send Rs. 100 to:</p>
                            <p class="fw-bold">03XX-XXXXXXX</p>
                            <p class="small text-muted">Note: Lucky Withdraw Entry</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h6 class="text-warning">JazzCash</h6>
                            <p class="small">Send Rs. 100 to:</p>
                            <p class="fw-bold">03XX-XXXXXXX</p>
                            <p class="small text-muted">Note: Lucky Withdraw Entry</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h6 class="text-info">Bank Transfer</h6>
                            <p class="small">Transfer Rs. 100 to:</p>
                            <p class="fw-bold">Account: XXXXXXXX</p>
                            <p class="small text-muted">Note: Lucky Withdraw Entry</p>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <strong>Important:</strong> Always include "Lucky Withdraw Entry" in the payment note/description.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectPaymentMethod(method) {
    // Remove selected class from all cards
    document.querySelectorAll('.payment-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card
    event.currentTarget.classList.add('selected');
    
    // Check the radio button
    document.getElementById(method).checked = true;
}

// Form submission with validation
document.getElementById('entryForm').addEventListener('submit', function(e) {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    const transactionId = document.getElementById('transaction_id').value.trim();
    const terms = document.getElementById('terms').checked;
    
    if (!paymentMethod) {
        e.preventDefault();
        alert('Please select a payment method.');
        return;
    }
    
    if (!transactionId) {
        e.preventDefault();
        alert('Please enter your transaction ID.');
        return;
    }
    
    if (!terms) {
        e.preventDefault();
        alert('Please accept the terms and conditions.');
        return;
    }
    
    // Disable submit button to prevent double submission
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Submitting...';
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
