@extends('layout.master')

@push('plugin-styles')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.3.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@endpush

@section('content')
    <div class="nav-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.lucky-withdraw.index') }}">Lucky Withdraw</a></li>
            <li class="breadcrumb-item active">Current Round Contestants</li>
            <a href="{{ route('admin.lucky-withdraw.index') }}" class="btn btn-sm btn-secondary breadcrumb-button">
                <i class="fa-solid fa-arrow-left"></i> Back to All Entries
            </a>
        </ol>
    </div>

    <!-- Round Info Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if($activeRound)
                        <h4 style="font-weight: bold;" class="mb-3">
                            <i class="fa-solid fa-trophy text-warning"></i> Round #{{ $activeRound->round_number }} Information
                        </h4>
                        <div class="row">
                            <div class="col-md-3">
                                <p class="mb-1"><strong>Round Start:</strong></p>
                                <p class="text-primary">{{ $activeRound->start_date->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1"><strong>Round End:</strong></p>
                                <p class="text-primary">{{ $activeRound->end_date->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1"><strong>Total Contestants:</strong></p>
                                <p class="text-success" style="font-size: 1.5rem; font-weight: bold;">{{ $contestants->total() }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1"><strong>Round Status:</strong></p>
                                @if($activeRound->status === 'active' && $activeRound->isInDateRange())
                                    <span class="badge badge-success" style="font-size: 1rem;">Active</span>
                                @else
                                    <span class="badge badge-danger" style="font-size: 1rem;">Ended</span>
                                @endif
                            </div>
                        </div>

                        @if($activeRound->winner_id)
                            <div class="alert alert-success mt-3">
                                <h5><i class="fa-solid fa-crown"></i> Winner Already Selected!</h5>
                                <p class="mb-0">
                                    <strong>Winner:</strong> {{ $activeRound->winner->name }} 
                                    ({{ $activeRound->winner->email }}) <br>
                                    <strong>Entry Code:</strong> {{ $activeRound->winnerEntry->entry_code }}
                                </p>
                            </div>
                        @else
                            <div class="alert alert-info mt-3">
                                <h5><i class="fa-solid fa-info-circle"></i> Winner Selection Available</h5>
                                <p class="mb-0">
                                    You can select a winner randomly or choose a specific contestant below.
                                </p>
                                <form method="POST" action="{{ route('admin.lucky-withdraw.select-winner') }}" 
                                      onsubmit="return confirm('Are you sure you want to randomly select a winner from all contestants? This action cannot be undone.');" 
                                      class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-warning" style="background-color: #ffc107; border-color: #ffc107;">
                                        <i class="fa-solid fa-dice"></i> Select Winner Randomly
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <h5><i class="fa-solid fa-exclamation-triangle"></i> No Active Round</h5>
                            <p class="mb-0">There is no active round. Please start a new round first.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.lucky-withdraw.contestants') }}" class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." 
                           value="{{ $request->search ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Search</button>
                    <a href="{{ route('admin.lucky-withdraw.contestants') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Contestants Table -->
    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;">Lucky Draw Contestants</h4>
            <p class="text-muted mb-0">Total Contestants: {{ $contestants->total() }} | Showing {{ $contestants->count() }} results</p>
        </div>
        <div class="card-body">
            @if($contestants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id='responsive-datatables'>
                        <thead>
                            <tr>
                                <th>Sr</th>
                                <th>Entry Code</th>
                                <th>User Info</th>
                                <th>Contact</th>
                                <th>Entry Date</th>
                                <th>Active Plans</th>
                                <th>Plan 3 History</th>
                                <th>Eligibility</th>
                                @if(!$existingWinner)
                                <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ser = ($contestants->currentPage() - 1) * $contestants->perPage();
                            @endphp
                            @foreach ($contestants as $contestant)
                                @php
                                    // Check user's eligibility
                                    $hasSubscribedPlan3 = $contestant->user->subscriptions->where('investment_plan_id', 3)->count() > 0;
                                    $activePlansCount = $contestant->user->subscriptions->filter(function($sub) {
                                        return $sub->expire_at >= now() && !$sub->deleted_at;
                                    })->count();
                                    
                                    $isEligibleForPlan = !$hasSubscribedPlan3 && $activePlansCount < 2;
                                    $rewardType = $isEligibleForPlan ? 'Plan 3 Subscription' : 'Rs. 7000 Bonus';
                                @endphp
                                <tr>
                                    <td>{{ ++$ser }}</td>
                                    <td>
                                        <strong class="text-primary">{{ $contestant->entry_code }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $contestant->user->name }}</strong><br>
                                            <small class="text-muted">{{ $contestant->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $contestant->user->contact ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($contestant->created_at)->format('d M Y, h:i A') }}
                                    </td>
                                    <td>
                                        @if($activePlansCount > 0)
                                            <span class="badge badge-success">{{ $activePlansCount }} Active</span>
                                        @else
                                            <span class="badge badge-secondary">No Active Plans</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasSubscribedPlan3)
                                            <span class="badge badge-warning">Already Subscribed</span>
                                        @else
                                            <span class="badge badge-success">Never Subscribed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>Reward:</strong>
                                        @if($isEligibleForPlan)
                                            <span class="badge badge-primary">{{ $rewardType }}</span>
                                        @else
                                            <span class="badge badge-info">{{ $rewardType }}</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">
                                            @if(!$isEligibleForPlan)
                                                @if($hasSubscribedPlan3)
                                                    Plan 3 already subscribed<br>
                                                @endif
                                                @if($activePlansCount >= 2)
                                                    Max active plans reached
                                                @endif
                                            @else
                                                Eligible for Plan 3
                                            @endif
                                        </small>
                                    </td>
                                    @if(!$existingWinner)
                                    <td>
                                        <form method="POST" action="{{ route('admin.lucky-withdraw.select-specific-winner', $contestant->id) }}" 
                                              onsubmit="return confirm('Are you sure you want to select {{ $contestant->user->name }} as the winner? This action cannot be undone.');" 
                                              style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Select as Winner">
                                                <i class="fa-solid fa-crown"></i> Select Winner
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $contestants->links() }}
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fa-solid fa-exclamation-triangle"></i> No contestants found for the current round.
                </div>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 style="font-weight: bold;"><i class="fa-solid fa-info-circle"></i> Winner Selection Information</h5>
            <ul class="mb-0">
                <li><strong>Plan 3 Subscription:</strong> If the winner has never subscribed to Plan 3 and has less than 2 active plans, they will be subscribed to Plan 3.</li>
                <li><strong>Rs. 7000 Bonus:</strong> If the winner has already subscribed to Plan 3 or has 2 active plans (upper limit), Rs. 7000 will be added to their Main Wallet as bonus.</li>
                <li><strong>Winner Selection:</strong> You can select a winner randomly or choose a specific contestant from the list above.</li>
                <li><strong>One-Time Action:</strong> Once a winner is selected, the action cannot be undone for this round.</li>
            </ul>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="https://cdn.datatables.net/rowreorder/1.3.3/js/dataTables.rowReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#responsive-datatables').DataTable({
                responsive: true,
                ordering: false,
                paging: false,
                searching: false,
                info: false
            });
        });
    </script>
@endpush

