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
                            <td><img src="{{ asset('/storage/' . $item['picture_proof']) }}"
                                    style="width: 100px;border-radius:unset!important;"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
