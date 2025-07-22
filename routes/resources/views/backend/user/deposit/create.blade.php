@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

    <div class="row">
        <div class="col-lg-4 offset-lg-4 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-header" style="font-weight: bold;">
                    Please upload deposit slip
                </div>
                <div class="card-body">
                    <form action="{{ route($route[0] . '.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user" value="{{Auth::user()['id']}}">
                        <div class="form-group">
                            <label for="bank">Bank *</label>
                            <select name="bank" id="bank" class="form-control" data-placeholder="Select" style="font-size: 14px;" required>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank['id'] }}">
                                        {{ $bank['bank_name'] . '-' . $bank['account_number'] . ' (' . $bank['account_title'] . ')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount *</label>
                            <input type="number" name="amount" min="0" id="amount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="transaction_id">Transaction ID</label>
                            <input type="text" name="transaction_id" min="0" id="transaction_id" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="date">Date *</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="proof">Transaction Proof *</label>
                            <input type="file" name="proof" id="proof" class="form-control" required>
                        </div>
                        <div class="form-group text-center">
                            <input type="submit" name="submit" value="submit" class="btn btn-success">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/dashboard.js') !!}
@endpush
