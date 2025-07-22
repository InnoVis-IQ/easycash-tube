@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
    
     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.3.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@endpush

@section('content')
    @php
        $route = explode('.', Route::currentRouteName());
    @endphp
    <div class="nav-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ route($route[0].'.index') }}">{{ ucfirst($route[0]) }}</a></li>
            {{-- <a href="{{ route($route[0].'.create') }}" class="btn btn-sm btn-primary breadcrumb-button"><i class="fa-solid fa-circle-plus"></i> Add</a> --}}
        </ol>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;">Investment Plans</h4>
        </div>
        <div class="card-body">
            <table class="table" id="responsive-datatables">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Daily Profit</th>
                        <th>Total Profit</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $item)
                        @if (Auth::user()['role'] != 'SUPERADMIN' && in_array($item['role'], ['SUPERADMIN', 'ADMIN']))
                            @continue
                        @endif
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item['title'] }}</td>
                            <td>{{ $item['amount'] }}</td>
                            <td>{{ $item['daily_profit'] }}</td>
                            <td>{{ $item['total_profit'] }}</td>
                            <td>{{ $item['status'] }}</td>
                            <td>
                                {!! actionButtons($item) !!}
                            </td>
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
   <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.3.3/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    {!! Html::script('/assets/js/dashboard.js') !!}
    
    <script>
        
        $(document).ready(function() {
            var table = $('#responsive-datatables').DataTable( {
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true
            } );
        } );
    </script>
@endpush
