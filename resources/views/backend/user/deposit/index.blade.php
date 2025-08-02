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
    @if (Session::has('UserSessionVars.InsufficientBalance') && Session::get('UserSessionVars.InsufficientBalance'))
        <div class="row">
            <div class="col-12 stretch-card mb-3">
                <div class="alert alert-warning" style="font-size: 150%;">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    You have insufficient balance in your account. Please deposit sufficient balance and wait for approval.<br>
                    Once the deposit is approved, please subscribe your desired package. As the deposit needs to be approved
                    so the package will not be automatically subscribed.
                    <br>
                    <div class="text-center">
                        <a href="{{ route('deposits.create') }}" class="btn btn-primary" style="font-size: 150%;">
                            <i class="fa-solid fa-piggy-bank"></i> Deposit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @php
            Session::put('UserSessionVars.InsufficientBalance', 0);
        @endphp
    @endif

    <div class="row">
        <div class="col-12 col-md-4 stretch-card mb-3">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-coins fa-2xl"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Total Balance</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">
                                    @php
                                        $userAccount = App\Models\UserAccount::where('user_id', Auth::user()['id'])->first();
                                        echo $userAccount['net_amount'] . ' PKR';
                                    @endphp
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 stretch-card mb-3">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-coins fa-2xl"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Total Deposits</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $totalDeposit }} PKR</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 stretch-card mb-3">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-plus-circle fa-2xl text-success"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">New Deposit</p>
                            <div class="fluid-container">
                                <a href="{{ route('deposits.create') }}" class="btn btn-success">
                                    <i class="fa-solid fa-plus"></i> Add Deposit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;"><i class="fa-solid fa-history me-2"></i>Deposit History</h4>
        </div>
        <div class="card-body">
            @if($data->count() > 0)
                <div class="table-responsive">
                    <table class="table" id="responsive-datatables">
                        <thead>
                            <tr>
                                <th>Sr</th>
                                <th>Amount</th>
                                <th>Bank</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Screenshot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                @if (Auth::user()['role'] != 'SUPERADMIN' && in_array($item['role'], ['SUPERADMIN', 'ADMIN']))
                                    @continue
                                @endif
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item['amount'] }}</td>
                                    <td>{{ $item['bank']['bank_name'] . ' (' . $item['bank']['account_number'] . ')' }}</td>
                                    <td>{{ date('d-M-Y', strtotime($item['deposit_date'])) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item['status'] == 'approved' ? 'success' : ($item['status'] == 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($item['status']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <img src="{{ asset($item['picture_proof']) }}" 
                                             data-toggle="modal" 
                                             data-target="#img{{ $key }}" 
                                             style="border-radius:unset!important;cursor:pointer;max-width:50px;">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No deposits found</h5>
                    <p class="text-muted">You haven't made any deposits yet.</p>
                    <a href="{{ route('deposits.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-2"></i>Make Your First Deposit
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Modals for image enlargement -->
    @foreach ($data as $key => $item)
        @if (Auth::user()['role'] != 'SUPERADMIN' && in_array($item['role'], ['SUPERADMIN', 'ADMIN']))
            @continue
        @endif
        <!-- Modal -->
        <div class="modal fade" id="img{{ $key }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Deposit Proof - {{ $item['transaction_id'] }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <center><img src="{{ asset($item['picture_proof']) }}"
                                style="max-height: 70vh;"></center>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
