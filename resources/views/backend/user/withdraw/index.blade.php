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
            {{-- <a href="{{ route($route[0] . '.create') }}" class="btn btn-sm btn-primary breadcrumb-button"><i
                    class="fa-solid fa-circle-plus"></i> Add</a> --}}
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

    <div class="card mb-3">
        <div class="card-header">
            <h4 style="font-weight: bold;">Request Withdrawal</h4>
        </div>
        <div class="card-body">
            <form action="{{route('withdraws.store')}}" method="POST" class="form row">
                @csrf
                <input type="hidden" name="userID" value="{{Auth::user()['id']}}">
                <div class="col-md-8 offset-md-2">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label>EasyPaisa Account Number</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="acc_number" placeholder="03001234567" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label>EasyPaisa Account Title</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="acc_title" placeholder="MUHAMMAD AHMED" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label>Amount</label>
                        </div>
                        <div class="col-md-7">
                            <input type="number" name="amount" max="{{ $netAmount }}" placeholder="500"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="text-center">
                            <input type="submit" name="submit" value="Request Withdrawal" class="btn btn-warning">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;">Withdrawal History</h4>
        </div>
        <div class="card-body">
            [Coming Soon]]
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
