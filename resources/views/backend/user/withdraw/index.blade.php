@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.3.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@endpush

@section('content')
    @php
        $route = explode('.', Route::currentRouteName());
    @endphp
    <div class="nav-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ route($route[0] . '.index') }}">{{ ucfirst($route[0]) }}</a></li>
        </ol>
    </div>

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
                            <p class="mb-0 text-right">Account Balance after approval</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $netAmount }} PKR</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal Rules Reminder -->
    <div class="card mb-3">
        <div class="card-header bg-info text-white">
            <h5><i class="fas fa-info-circle"></i> Withdrawal Rules</h5>
        </div>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                <li><i class="fas fa-check text-success"></i> Minimum withdrawal amount: Rs. 500</li>
                <li><i class="fas fa-check text-success"></i> Maximum withdrawal amount: Rs. 50,000</li>
                <li><i class="fas fa-check text-success"></i> You must have an active subscription to withdraw</li>
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
                            </select>
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
                            <input type="number" name="amount" min="500" max="{{ $netAmount }}" placeholder="500"
                                class="form-control" required>
                            <small class="text-muted">Minimum: Rs. 500, Maximum: Rs. {{ $netAmount }}</small>
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
                <h4>You are not eligible to withdraw</h4>
                <p>You must have a valid subscription to withdraw. <a href="{{ route('plans.index') }}" class="btn btn-primary">Subscribe to a plan</a></p>
                <p>
                    Please refer to our <a href="{{ url('/terms-and-conditions') }}" target="_blank">Terms and Conditions</a> for more information about withdrawal eligibility.
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
                                        @if($withdrawal->user_bank)
                                            {{ ucfirst($withdrawal->user_bank->account_title ?? 'N/A') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($withdrawal->user_bank)
                                            {{ $withdrawal->user_bank->account_number ?? 'N/A' }}
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
