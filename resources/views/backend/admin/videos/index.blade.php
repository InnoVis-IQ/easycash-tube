@extends('layout.master')

@push('plugin-styles')
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
            <a href="{{ route($route[0].'.create') }}" class="btn btn-sm btn-primary breadcrumb-button"><i class="fa-solid fa-circle-plus"></i> Add</a>
        </ol>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;">Videos</h4>
        </div>
        <div class="card-body">
            <table class="table" id="responsive-datatables">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Plan</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Video (Views)</th>
                        <th>Video Thumbnail</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $item)
                        <tr>
                            <td class="{{$item['deleted_at'] ? 'bg-secondary' : ''}}">{{ $key + 1 }}</td>
                            <td class="{{$item['deleted_at'] ? 'bg-secondary' : ''}}">{{ $item->plan->title }}</td>
                            <td class="{{$item['deleted_at'] ? 'bg-secondary' : ''}}">{{ $item->title }}</td>
                            <td class="{{$item['deleted_at'] ? 'bg-secondary' : ''}}">{{ $item->description }}</td>
                            <td class="{{$item['deleted_at'] ? 'bg-secondary' : ''}}"><a href="{{ $item->video_url }}" target="_blank">View</a> ({{ $videoWatchCounts[$item->id] ?? 0 }})</td>
                            <td class="{{$item['deleted_at'] ? 'bg-secondary' : ''}}">
                                @if($item->video_thumbnail)
                                    <img src="{{ $item->video_thumbnail }}" alt="Thumbnail" style="max-width: 80px; max-height: 50px;">
                                @endif
                            </td>
                            <td class="{{$item['deleted_at'] ? 'bg-secondary' : ''}}">{{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('Y-m-d') : '' }}</td>
                            <td class="{{$item['deleted_at'] ? 'bg-secondary' : ''}}">{{ $item->status }}</td>
                            <td class="{{$item['deleted_at'] ? 'bg-secondary' : ''}}">
                                <a href="{{ route($route[0].'.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route($route[0].'.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
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