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
            <a href="{{ route($route[0] . '.create') }}" class="btn btn-sm btn-primary breadcrumb-button"><i
                    class="fa-solid fa-circle-plus"></i> Add</a>
        </ol>
    </div>

    @if (Session::has('UserSessionVars.InsufficientBalance') && Session::get('UserSessionVars.InsufficientBalance'))
        <div class="row">
            <div class="col-12 stretch-card mb-3">
                <div class="alert alert-warning" style="font-size: 150%;">
                    You have insufficient balance in your account. Please deposite sufficient balance and wait for
                    approval.<br>
                    Once the deposit is approved, please subscribe your desired package. As the deposit needs to be approved
                    so the package will not be automatically subscribed.
                    <br>
                    <div class="text-center"><a href="{{ route($route[0] . '.create') }}" class="btn btn-sm btn-primary"
                            style="font-size: 150%;"><i class="fa-solid fa-piggy-bank"></i> Deposit</a></div>
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
            <div class="card card-statistics" hidden>
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-coins fa-2xl"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Total Approved</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $approvedDeposit }} PKR</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;">Deposit History</h4>
        </div>
        <div class="card-body">
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
                            <td>{{ $item['status'] }}</td>
                            <td><img src="{{ asset($item['picture_proof']) }}" data-toggle="modal" data-target="#img{{ $key }}" style="border-radius:unset!important;cursor:pointer;"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
