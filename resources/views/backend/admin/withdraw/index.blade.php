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
            {{-- <a href="{{ route($route[0].'.create') }}" class="btn btn-sm btn-primary breadcrumb-button"><i class="fa-solid fa-circle-plus"></i> Add</a> --}}
        </ol>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 mx-auto  stretch-card mb-4">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-coins fa-2xl"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Pending Approval</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $pending }}</h3>
                                <p class="mb-0 text-right"> request(s)</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;">Withdraw History</h4>
        </div>
        <div class="card-body">
            <table class="table " id="responsive-datatables">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>User</th>
                        <th>Account Balance</th>
                        <th>Amount</th>
                        <th>Account Detail</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $item)
                        @if (Auth::user()['role'] != 'SUPERADMIN' && in_array($item['role'], ['SUPERADMIN', 'ADMIN']))
                            @continue
                        @endif
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item['user']['name'] }}</td>
                            <td>{!! $item['status'] == 'requested' ? $accountBalance[$item['user_id']] : 'Account Updated<br>after Withdrawal' !!}</td>
                            <td>{{ $item['amount'] }}</td>
                            <td>{{ $item['user_bank']['account_number'] }}<br>{{ $item['user_bank']['account_title'] }}</td>
                            <td>{{ date('d-M-Y', strtotime($item['created_at'])) }}</td>
                            <td>{{ $item['status'] }}</td>
                            <td>
                                @if ($item['status'] == 'requested')
                                    <a href="" data-toggle="modal" data-target="#action{{ $key }}"
                                        class="btn btn-success"><i class='fa-regular fa-circle-check'></i> Transferred</a>
                                    {{-- <form method="POST" action="{{ route($route[0] . '.update', $item['id']) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="status" value="transferred" class="btn btn-success"><i
                                                class='fa-regular fa-circle-check'></i> Transferred</button>
                                    </form> --}}
                                @else
                                    {{-- {!! actionButtons($item) !!} --}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($data as $key => $item)
        <!-- Modal -->
        <div class="modal fade" id="action{{ $key }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class='fa-regular fa-circle-check'></i> Tranferred
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route($route[0] . '.update', $item['id']) }}">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label for="bank">Transferred from Bank *</label>
                                <select name="bank" id="bank" class="form-control" data-placeholder="Select"
                                    style="font-size: 14px;" required>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank['id'] }}">
                                            {{ $bank['bank_name'] . '-' . $bank['account_number'] . ' (' . $bank['account_title'] . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="status" value="transferred" class="btn btn-success"><i
                                    class='fa-regular fa-circle-check'></i>
                                Transferred</button>
                        </form>
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
