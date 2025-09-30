@extends('layouts.master')

@section('title', 'Lucky Withdraw')

@push('styles')
<style>
    .lucky-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .winner-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .stats-card {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .countdown-timer {
        font-size: 2rem;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .entry-status {
        padding: 8px 16px;
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
    
    .status-winner {
        background-color: #ffeaa7;
        color: #6c5ce7;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="lucky-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2"><i class="fa-solid fa-gift me-3"></i>Lucky Withdraw Contest</h2>
                            <p class="mb-0">Deposit Rs. 100 for a chance to win Plan 3 (Rs. 7,000) FREE!</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="countdown-timer" id="countdown">
                                <!-- Countdown will be populated by JavaScript -->
                            </div>
                            <small>Days Remaining</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Round Information -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stats-card">
                <div class="card-body p-3">
                    <h6 class="mb-2"><i class="fa-solid fa-calendar me-2"></i>Current Round</h6>
                    <p class="mb-1"><strong>Start:</strong> {{ \Carbon\Carbon::parse($currentRoundStart)->format('M d, Y') }}</p>
                    <p class="mb-0"><strong>End:</strong> {{ \Carbon\Carbon::parse($currentRoundEnd)->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card">
                <div class="card-body p-3">
                    <h6 class="mb-2"><i class="fa-solid fa-users me-2"></i>Participants</h6>
                    <h4 class="mb-0">{{ $currentRoundEntries }} entries</h4>
                    <small>Approved participants</small>
                </div>
            </div>
        </div>
    </div>

    <!-- User Entry Status -->
    @if($userEntry)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-2"><i class="fa-solid fa-ticket me-2"></i>Your Entry Status</h5>
                                <p class="mb-2"><strong>Entry Code:</strong> {{ $userEntry->entry_code }}</p>
                                <p class="mb-2"><strong>Amount:</strong> Rs. {{ number_format($userEntry->entry_amount) }}</p>
                                <p class="mb-2"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $userEntry->payment_method)) }}</p>
                                <p class="mb-2"><strong>Transaction ID:</strong> {{ $userEntry->transaction_id }}</p>
                                <p class="mb-2"><strong>Submitted:</strong> {{ $userEntry->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="col-md-4 text-center">
                                @if($userEntry->is_winner)
                                    <div class="entry-status status-winner mb-3">
                                        <i class="fa-solid fa-crown me-2"></i>WINNER!
                                    </div>
                                    <p class="text-success mb-0"><strong>Congratulations! You won Plan 3!</strong></p>
                                @else
                                    <div class="entry-status 
                                        @if($userEntry->status == 'pending') status-pending
                                        @elseif($userEntry->status == 'approved') status-approved
                                        @elseif($userEntry->status == 'rejected') status-rejected
                                        @endif mb-3">
                                        {{ ucfirst($userEntry->status) }}
                                    </div>
                                    @if($userEntry->status == 'approved')
                                        <p class="text-success mb-0">You're in the contest!</p>
                                    @elseif($userEntry->status == 'pending')
                                        <p class="text-warning mb-0">Waiting for approval</p>
                                    @elseif($userEntry->status == 'rejected')
                                        <p class="text-danger mb-0">{{ $userEntry->admin_notes ?? 'Entry rejected' }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Entry Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mb-3"><i class="fa-solid fa-plus-circle me-2"></i>Enter Lucky Withdraw</h5>
                                <p class="mb-3">Deposit Rs. 100 to participate in the current round and get a chance to win Plan 3 (Rs. 7,000) absolutely free!</p>
                                
                                @if($isRoundActive)
                                    <a href="{{ route('lucky-withdraw.create') }}" class="btn btn-primary btn-lg">
                                        <i class="fa-solid fa-ticket me-2"></i>Enter Contest (Rs. 100)
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-lg" disabled>
                                        <i class="fa-solid fa-clock me-2"></i>Round Not Active
                                    </button>
                                    <p class="text-muted mt-2">Please wait for the next round to start.</p>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h6 class="text-primary">Prize</h6>
                                    <h3 class="text-success">Plan 3</h3>
                                    <p class="mb-2">Rs. 7,000 Investment Plan</p>
                                    <p class="mb-2">15 Days Duration</p>
                                    <p class="mb-2">Rs. 700 Daily Profit</p>
                                    <p class="mb-0">Total Profit: Rs. 10,500</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Winners -->
    @if($recentWinners->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="winner-card">
                    <div class="card-body p-4">
                        <h5 class="mb-3"><i class="fa-solid fa-trophy me-2"></i>Recent Winners</h5>
                        <div class="row">
                            @foreach($recentWinners as $winner)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card bg-light text-dark">
                                        <div class="card-body p-3 text-center">
                                            <div class="mb-2">
                                                <i class="fa-solid fa-crown fa-2x text-warning"></i>
                                            </div>
                                            <h6 class="mb-1">{{ $winner->user->name }}</h6>
                                            <p class="mb-1 small">Entry Code: {{ $winner->entry_code }}</p>
                                            <p class="mb-0 small text-muted">{{ $winner->updated_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- User Entry History -->
    @if($userEntries->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header p-3">
                        <h6 class="mb-0"><i class="fa-solid fa-history me-2"></i>Your Entry History</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Entry Code</th>
                                        <th>Round</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userEntries as $entry)
                                        <tr>
                                            <td><code>{{ $entry->entry_code }}</code></td>
                                            <td>{{ \Carbon\Carbon::parse($entry->round_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($entry->round_end_date)->format('M d') }}</td>
                                            <td>Rs. {{ number_format($entry->entry_amount) }}</td>
                                            <td>
                                                @if($entry->is_winner)
                                                    <span class="entry-status status-winner">
                                                        <i class="fa-solid fa-crown me-1"></i>WINNER
                                                    </span>
                                                @else
                                                    <span class="entry-status 
                                                        @if($entry->status == 'pending') status-pending
                                                        @elseif($entry->status == 'approved') status-approved
                                                        @elseif($entry->status == 'rejected') status-rejected
                                                        @endif">
                                                        {{ ucfirst($entry->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $entry->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('lucky-withdraw.show', $entry) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- How It Works Modal -->
<div class="modal fade" id="howItWorksModal" tabindex="-1" aria-labelledby="howItWorksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="howItWorksModalLabel">
                    <i class="fa-solid fa-question-circle me-2"></i>How Lucky Withdraw Works
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Participation</h6>
                        <ol>
                            <li>Deposit Rs. 100 via EasyPaisa, JazzCash, or Bank Transfer</li>
                            <li>Submit your entry with transaction ID</li>
                            <li>Wait for admin approval</li>
                            <li>Once approved, you're in the contest!</li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Winning</h6>
                        <ol>
                            <li>Every 15 days, one winner is selected randomly</li>
                            <li>Winner gets Plan 3 (Rs. 7,000) absolutely free</li>
                            <li>Plan is automatically activated in winner's account</li>
                            <li>Winner can start earning Rs. 700 daily profit</li>
                        </ol>
                    </div>
                </div>
                <hr>
                <div class="alert alert-info">
                    <h6><i class="fa-solid fa-info-circle me-2"></i>Important Notes:</h6>
                    <ul class="mb-0">
                        <li>Only one entry per user per round</li>
                        <li>Entry fee must be paid from personal funds (not from earnings/bonus)</li>
                        <li>Winner selection is completely random and fair</li>
                        <li>All approved entries have equal chance of winning</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update countdown timer
    function updateCountdown() {
        const endDate = new Date('{{ $currentRoundEnd }}').getTime();
        const now = new Date().getTime();
        const distance = endDate - now;
        
        if (distance < 0) {
            document.getElementById('countdown').innerHTML = "0";
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        document.getElementById('countdown').innerHTML = days;
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
    
    // Auto-refresh stats every 30 seconds
    setInterval(function() {
        fetch('{{ route("lucky-withdraw.stats") }}')
            .then(response => response.json())
            .then(data => {
                // Update participant count
                const participantElement = document.querySelector('.stats-card .h4');
                if (participantElement) {
                    participantElement.textContent = data.approved_entries + ' entries';
                }
            })
            .catch(error => console.log('Error fetching stats:', error));
    }, 30000);
});
</script>
@endpush
