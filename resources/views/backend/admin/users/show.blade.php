@extends('layout.master')

@push('plugin-styles')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.3.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@endpush

@section('content')
    @php
        $route = explode('.', Route::currentRouteName());
    @endphp

    @php
        $invested = 0;
        $earned = 0;
        $refEarnings = 0;
    @endphp

    <div class="nav-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route($route[0] . '.index') }}">{{ ucfirst($route[0]) }}</a></li>
            <li class="breadcrumb-item active"><a
                    href="{{ route($route[0] . '.show', $data['id']) }}">{{ $data['name'] }}</a>
            </li>
            {{-- <a href="{{ route($route[0].'.create') }}" class="btn btn-sm btn-primary breadcrumb-button"><i class="fa-solid fa-circle-plus"></i> Add</a> --}}
        </ol>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            <h4 style="font-weight: bold;">{{ $data['name'] }}</h4>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header row m-0">
            <div class="col-md-8" style="float: left;">
                <h4 style="font-weight: bold;">Investment Plans</h4>
            </div>
            <div class="col-md-4" style="float: right">
                <div class="row">
                    <div class="col-8 text-left">Life-time Investment:</div>
                    <div class="col-4 text-right"><span id="invested"></span> PKR</div>
                </div>
                <div class="row">
                    <div class="col-8 text-left">Life-time Earning:</div>
                    <div class="col-4 text-right"><span id="earned"></span> PKR</div>
                </div>
            </div>
        </div>
        <div class="card-body row">
            @foreach ($subscriptions as $subscription)
                @php
                    $remainingDays = (strtotime(date('Y-m-d', strtotime($subscription['expire_at']))) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
                    $invested += $subscription['plan']['amount'];
                    $earned += $subscription['plan']['daily_profit'] * ($subscription['plan']['plan_days'] - $remainingDays);
                @endphp
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card border border-info">
                        <div class="card-header bg-primary text-white text-center p-0">
                            {{ $subscription['plan']['title'] }}
                        </div>
                        <div class="card-body p-0 px-1">
                            <div class="row">
                                <div class="col-7 text-left">Plan cost:</div>
                                <div class="col-5 text-right">{{ $subscription['plan']['amount'] }} PKR</div>
                            </div>
                            <div class="row">
                                <div class="col-7 text-left">Daily Profit:</div>
                                <div class="col-5 text-right">{{ $subscription['plan']['daily_profit'] }} PKR</div>
                            </div>
                            <div class="row">
                                <div class="col-7 text-left">Profit Earned:</div>
                                <div class="col-5 text-right">
                                    {{ $subscription['earning'] }}
                                    PKR</div>
                            </div>
                            <div class="row">
                                <div class="col-7 text-left">Plan Ending in:</div>
                                <div class="col-5 text-right">{{ $remainingDays }} days</div>
                            </div>
                            <div class="row">
                                <div class="col-7 text-left">Total Profit:</div>
                                <div class="col-5 text-right">{{ $subscription['plan']['total_profit'] }} PKR</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header row m-0">
            <div class="col-md-8" style="float: left;">
                <h4 style="font-weight: bold;">Referrals</h4>
            </div>
            <div class="col-md-4" style="float: right">
                <div class="row">
                    <div class="col-8 text-left">Referrals Earning:</div>
                    <div class="col-4 text-right"><span id="invested"></span> PKR</div>
                </div>
            </div>
        </div>
        <div class="card-body row">
            [coming soon]
            <table class="table table-responsive" hidden>
                <thead>
                    <tr>
                        <th>Ser</th>
                        <th>Name</th>
                        <th>Earning</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('plugin-scripts')
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

            $("#invested").html({{$invested}});
            $("#earned").html({{$earned}});
        });
    </script>
@endpush
