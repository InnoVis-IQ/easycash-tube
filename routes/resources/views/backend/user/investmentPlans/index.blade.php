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

    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;">My Subscribed Investment Plans</h4>
        </div>
        <div class="card-body">
            <table class="table" id='responsive-datatables'>
                <thead>
                    <tr>
                        <th class="text-center">Sr</th>
                        <th class="text-center">Plan</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Daily Profit</th>
                        <th class="text-center">Total Profit</th>
                        <th class="text-center">Duration</th>
                        <th class="text-center">Subscription Date</th>
                        {{-- <th class="text-center">Maturity Date</th> --}}
                        <th class="text-center">Profit Earned</th>
                        {{-- <th class="text-center">Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $item)
                        @if (Auth::user()['role'] != 'SUPERADMIN' && in_array($item['role'], ['SUPERADMIN', 'ADMIN']))
                            @continue
                        @endif
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{ $item['plan']['title'] }}</td>
                            <td class="text-center">{{ $item['plan']['amount'] }}</td>
                            <td class="text-center">{{ $item['plan']['daily_profit'] }}</td>
                            <td class="text-center">{{ $item['plan']['total_profit'] }}</td>
                            <td class="text-center">{{ $item['plan']['plan_days'] }}</td>
                            <td class="text-center">{{ date('d-M-Y', strtotime($item['created_at'])) }}</td>
                            {{-- <td class="text-center">{{ date('d-M-Y', strtotime($item['expire_at'])) }}</td> --}}
                            <td class="text-center">{{ $item['earning'] }}</td>
                            {{-- <td class="text-center">
                                <form action="{{ route('withdrawToAccount') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="userID" value="{{ $item['user_id'] }}">
                                    <input type="hidden" name="userSubsID" value="{{ $item['id'] }}">
                                    <input type="hidden" name="amount" value="{{ $item['earning'] }}">
                                    <button type="submit" class="btn btn-success">Withdraw to Account</button>
                                </form>
                            </td> --}}
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
