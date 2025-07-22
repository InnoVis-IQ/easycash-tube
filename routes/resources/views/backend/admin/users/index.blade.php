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
    <div class="nav-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ route($route[0] . '.index') }}">{{ ucfirst($route[0]) }}</a></li>
            {{-- <a href="{{ route($route[0].'.create') }}" class="btn btn-sm btn-primary breadcrumb-button"><i class="fa-solid fa-circle-plus"></i> Add</a> --}}
        </ol>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;">Users List</h4>
        </div>
        <div class="card-body">
            <table class="table" id='responsive-datatables'>
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Name</th>
                        <th>Reffered By</th>
                        <th>Net Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ser = 0;
                    @endphp
                    @foreach ($data as $key => $item)
                        @if (Auth::user()['role'] != 'SUPERADMIN' && in_array($item['role'], ['SUPERADMIN', 'ADMIN']))
                            @continue
                        @endif
                        @if ($item->trashed())
                            @continue
                        @endif
                        @php
                            $bg = $item->trashed() ? 'bg-secondary' : '';
                        @endphp
                        <tr>
                            <td class="{{ $bg }}">{{ ++$ser }}</td>
                            <td class="{{ $bg }}">{{ $item['name'] }}</td>
                            <td class="{{ $bg }}">
                                {{ $item['referred_by'] ? $referrals[$item['referred_by']] : 'NA' }}</td>
                            <td class="{{ $bg }}">{{ $item['userAccount']['net_amount'] ?? '-' }}</td>
                            <td class="{{ $bg }}">
                                @if ($item['role'] == 'RESTRICTED')
                                    <form method="POST" action="{{ route($route[0] . '.update', $item['id']) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="role" value="approve" class="btn btn-success"><i
                                                class='fa-regular fa-circle-check'></i> Approve</button>
                                    </form>
                                @else
                                    {!! actionButtons($item) !!}
                                @endif
                                @if(Auth::user()['role'] == 'SUPERADMIN')
                                <a href="{{route('adjustUserAccount',$item['id'])}}" class="btn btn-danger"><i class="fas fa-exclamation-triangle"></i> Set</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
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
        });
    </script>
@endpush
