@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.3.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@endpush

@section('content')
<div class="container-fluid px-2">
    @if(isset($message) && $message != '0')
        <div class="alert alert-info">
            {{ $message }}
        </div>
    @endif

    <!-- Eligibility Status -->
    @if(isset($eligibilityMessage) && $eligibilityMessage)
        <div class="alert alert-warning">
            <i class="fa-solid fa-exclamation-triangle me-2"></i>
            <strong>Withdrawal Eligibility:</strong> {{ $eligibilityMessage }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-3 offset-md-1 stretch-card mb-3">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-coins fa-2xl"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Account Balance</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $netAmount + $userWithdraws }} PKR</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card mb-3">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-coins fa-2xl"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Withdrawals Requested</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $userWithdraws }} PKR</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card mb-3">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-coins fa-2xl"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Available for Withdrawal</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $netAmount }} PKR</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Previous Plans Status -->
    @if(isset($previousPlans) && $previousPlans->count() > 0)
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-check-circle"></i> Completed Plans</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($previousPlans as $plan)
                        <div class="col-md-4 mb-2">
                            <div class="border rounded p-3">
                                <strong>{{ $plan->plan->title ?? 'Plan' }}</strong><br>
                                <small class="text-muted">Completed on {{ $plan->expire_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Withdrawal Rules Reminder -->
    <div class="card mb-3">
        <div class="card-header bg-info text-white">
            <h5><i class="fas fa-info-circle"></i> Withdrawal Rules</h5>
        </div>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                <li><i class="fas fa-check text-success"></i> Withdrawal requests are processed within 24-48 hours</li>
                <li><i class="fas fa-check text-success"></i> Supported payment methods: Easypaisa, JazzCash, NayaPay</li>
            </ul>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h4 style="font-weight: bold;">Request Withdrawal</h4>
        </div>
        <div class="card-body">
            @if($isEligible)
            <form action="{{route('withdraws.store')}}" method="POST" class="form row">
                @csrf
                <input type="hidden" name="userID" value="{{Auth::user()['id']}}">
                <div class="col-md-8 offset-md-2">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label>Payment Method</label>
                        </div>
                        <div class="col-md-7">
                            <select name="payment_method" class="form-control" required>
                                <option value="">Select Payment Method</option>
                                <option value="easypaisa">Easypaisa</option>
                                <option value="jazzcash">JazzCash</option>
                                <option value="nayapay">NayaPay</option>
                                <option value="bank">Bank</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label>Bank Name</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="bank_name" placeholder="HBL" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label>Account Number</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="acc_number" placeholder="03001234567" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label>Account Title</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="acc_title" placeholder="MUHAMMAD AHMED" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label>Amount (PKR)</label>
                        </div>
                        <div class="col-md-7">
                            <input type="number" name="amount" min="500" placeholder="500"
                                class="form-control" required>
                            <small class="text-muted">Minimum: Rs. 500</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="text-center">
                            <input type="submit" name="submit" value="Request Withdrawal" class="btn btn-warning">
                        </div>
                    </div>
                </div>
            </form>
            @else
            <div class="alert alert-danger">
                <h4><i class="fa-solid fa-lock me-2"></i>Withdrawals Not Available</h4>
                <p>
                    <a href="{{ route('plans.index') }}" class="btn btn-primary">
                        <i class="fa-solid fa-compass-drafting me-2"></i>View Plans
                    </a>
                    <a href="{{ route('videos.watch') }}" class="btn btn-success">
                        <i class="fa-solid fa-play me-2"></i>Watch Videos
                    </a>
                </p>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;">Withdrawal History</h4>
        </div>
        <div class="card-body">
            @if($withdrawalHistory->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Account Number</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawalHistory as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $withdrawal->amount }} PKR</td>
                                    <td>
                                        @if($withdrawal->userBankAccount)
                                            {{ ucfirst($withdrawal->userBankAccount->account_title ?? 'N/A') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($withdrawal->userBankAccount)
                                            {{ $withdrawal->userBankAccount->account_number ?? 'N/A' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $withdrawal->status == 'transferred' ? 'success' : ($withdrawal->status == 'requested' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center">No withdrawal history found.</p>
            @endif
        </div>
    </div>
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
    
    .form-control-sm {
        font-size: 0.875rem;
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
    
    .form-control-sm {
        font-size: 0.75rem;
    }
}
</style>

@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
@endpush

@push('custom-scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.3.3/js/dataTables.rowReorder.min.js">
    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js">
    </script>
    {!! Html::script('/assets/js/dashboard.js') !!}

    <script>
        $(document).ready(function() {
            var table = $('#responsive-datatables').DataTable({
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true
            });
        });
    </script>
@endpush
