@extends('layout.master')

@section('title', 'Lucky Withdraw Management')

@push('styles')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .status-badge {
        padding: 6px 12px;
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
            <div class="stats-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2"><i class="fa-solid fa-trophy me-3"></i>Lucky Withdraw Management</h2>
                            <p class="mb-0">Manage contest entries and select winners</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="mb-0">{{ $roundStats['total_entries'] }}</h4>
                            <small>Total Entries This Round</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Round Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body p-3 text-center">
                    <h5 class="mb-1">{{ $roundStats['total_entries'] }}</h5>
                    <small>Total Entries</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body p-3 text-center">
                    <h5 class="mb-1">{{ $roundStats['approved_entries'] }}</h5>
                    <small>Approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body p-3 text-center">
                    <h5 class="mb-1">{{ $roundStats['pending_entries'] }}</h5>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body p-3 text-center">
                    <h5 class="mb-1">{{ $roundStats['rejected_entries'] }}</h5>
                    <small>Rejected</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Round Information -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-calendar me-2"></i>Current Round</h6>
                </div>
                <div class="card-body p-3">
                    <p class="mb-2"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($currentRoundStart)->format('M d, Y') }}</p>
                    <p class="mb-2"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($currentRoundEnd)->format('M d, Y') }}</p>
                    <p class="mb-0"><strong>Status:</strong> 
                        @if(\Carbon\Carbon::parse($currentRoundEnd)->isFuture())
                            <span class="text-success">Active</span>
                        @else
                            <span class="text-danger">Ended</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-crown me-2"></i>Winner Selection</h6>
                </div>
                <div class="card-body p-3">
                    @if($roundStats['approved_entries'] > 0)
                        <a href="{{ route('admin.lucky-withdraw.contestants') }}" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="fa-solid fa-users me-2"></i>View Contestants & Select Winner
                        </a>
                        <form action="{{ route('admin.lucky-withdraw.select-winner') }}" method="POST" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100" 
                                    onclick="return confirm('Are you sure you want to randomly select a winner? This action cannot be undone.')">
                                <i class="fa-solid fa-dice me-2"></i>Random Winner Selection
                            </button>
                        </form>
                        <small class="text-muted d-block mt-2">
                            {{ $roundStats['approved_entries'] }} approved entries eligible for winner selection
                        </small>
                    @else
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            <i class="fa-solid fa-ban me-2"></i>No Eligible Entries
                        </button>
                        <small class="text-muted d-block mt-2">
                            No approved entries available for winner selection
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Round Management -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header p-3 bg-warning bg-opacity-10">
                    <h6 class="mb-0"><i class="fa-solid fa-tools me-2"></i>Round Management Controls</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        @php
                            $hasActiveEntries = $roundStats['approved_entries'] > 0;
                            $hasWinner = \App\Models\LuckyWithdrawEntry::currentRound()->where('is_winner', true)->exists();
                            $isRoundActive = \Carbon\Carbon::parse($currentRoundEnd)->isFuture();
                        @endphp
                        
                        <div class="col-md-6">
                            <div class="card mb-3 mb-md-0">
                                <div class="card-body p-3">
                                    <h6 class="mb-2"><i class="fa-solid fa-stop-circle text-danger me-2"></i>End Current Round</h6>
                                    <p class="small text-muted mb-3">Manually end the current round before the scheduled end date.</p>
                                    
                                    @if($hasActiveEntries && $hasWinner)
                                        <form action="{{ route('admin.lucky-withdraw.end-round') }}" method="POST" class="d-inline w-100">
                                            @csrf
                                            <button type="submit" class="btn btn-danger w-100" 
                                                    onclick="return confirm('Are you sure you want to end the current round? This will mark all entries as completed and allow you to start a new round.')">
                                                <i class="fa-solid fa-flag-checkered me-2"></i>End Current Round
                                            </button>
                                        </form>
                                        <small class="text-muted d-block mt-2">
                                            <i class="fa-solid fa-check-circle text-success"></i> Winner selected. Round can be ended.
                                        </small>
                                    @elseif($hasActiveEntries && !$hasWinner)
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fa-solid fa-ban me-2"></i>End Current Round
                                        </button>
                                        <small class="text-danger d-block mt-2">
                                            <i class="fa-solid fa-exclamation-circle"></i> Please select a winner first
                                        </small>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fa-solid fa-ban me-2"></i>No Active Round
                                        </button>
                                        <small class="text-muted d-block mt-2">
                                            No active entries to end
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <h6 class="mb-2"><i class="fa-solid fa-play-circle text-success me-2"></i>Start New Round</h6>
                                    <p class="small text-muted mb-3">Begin a new Lucky Draw round and notify all users.</p>
                                    
                                    @if(!$hasActiveEntries)
                                        <form action="{{ route('admin.lucky-withdraw.start-round') }}" method="POST" class="d-inline w-100">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100" 
                                                    onclick="return confirm('Are you sure you want to start a new round? All users will be notified about the new Lucky Draw.')">
                                                <i class="fa-solid fa-rocket me-2"></i>Start New Round
                                            </button>
                                        </form>
                                        <small class="text-muted d-block mt-2">
                                            <i class="fa-solid fa-info-circle text-info"></i> New 15-day round will begin today
                                        </small>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fa-solid fa-ban me-2"></i>Start New Round
                                        </button>
                                        <small class="text-danger d-block mt-2">
                                            <i class="fa-solid fa-exclamation-circle"></i> End current round first
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-0">
                        <small>
                            <strong><i class="fa-solid fa-lightbulb me-1"></i>Note:</strong> 
                            Round management allows you to control the Lucky Draw schedule manually. 
                            End the current round after selecting a winner, then start a new round to allow users to participate again. 
                            All users will be notified when a new round starts.
                        </small>
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
                    <form method="GET" action="{{ route('admin.lucky-withdraw.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select name="status" class="form-select form-control">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="round_start_date" class="form-control" 
                                       value="{{ request('round_start_date') }}" placeholder="Round Date">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Search by user name or email">
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

    <!-- Entries Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="fa-solid fa-list me-2"></i>Contest Entries</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Entry Code</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Transaction ID</th>
                                    <th>Round</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entries as $entry)
                                    <tr>
                                        <td>
                                            <code>{{ $entry->entry_code }}</code>
                                            @if($entry->is_winner)
                                                <i class="fa-solid fa-crown text-warning ms-2"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">{{ $entry->user->name }}</div>
                                                <small class="text-muted">{{ $entry->user->email }}</small>
                                            </div>
                                        </td>
                                        <td>Rs. {{ number_format($entry->entry_amount) }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $entry->payment_method)) }}</td>
                                        <td><code>{{ $entry->transaction_id }}</code></td>
                                        <td>
                                            <small>
                                                {{ \Carbon\Carbon::parse($entry->round_start_date)->format('M d') }} - 
                                                {{ \Carbon\Carbon::parse($entry->round_end_date)->format('M d') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($entry->is_winner)
                                                <span class="status-badge status-winner">
                                                    <i class="fa-solid fa-crown me-1"></i>WINNER
                                                </span>
                                            @else
                                                <span class="status-badge 
                                                    @if($entry->status == 'pending') status-pending
                                                    @elseif($entry->status == 'approved') status-approved
                                                    @elseif($entry->status == 'rejected') status-rejected
                                                    @endif">
                                                    {{ ucfirst($entry->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $entry->created_at->format('M d, Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($entry->status == 'pending')
                                                    <form action="{{ route('admin.lucky-withdraw.approve', $entry) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                onclick="return confirm('Approve this entry?')">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $entry->id }}">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('lucky-withdraw.show', $entry) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>

                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $entry->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Entry</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('admin.lucky-withdraw.reject', $entry) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="admin_notes" class="form-label">Reason for Rejection</label>
                                                                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Reject Entry</button>
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
                                                <p>No entries found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($entries->hasPages())
                    <div class="card-footer p-3">
                        {{ $entries->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
