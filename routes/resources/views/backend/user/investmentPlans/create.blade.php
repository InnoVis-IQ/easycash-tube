@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
    @php
        $route = explode('.', Route::currentRouteName());
    @endphp
    <div class="nav-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ route($route[0].'.index') }}">{{ ucfirst($route[0]) }}</a></li>
        </ol>
    </div>

    <div class="row">
        @foreach ($data as $key => $item)
            <div class="col-md-4">
                <div class="card my-3">
                    <div class="card-header p-0">
                        <div class="text-center bg-info text-white" style="font-weight: bold; line-height: 3;">
                            {{ $item['title'] }}</div>
                        <div class="px-4 py-2">
                            <div style="float: left; text-align: center;">
                                <span style="font-size: 24px; font-weight: bold;">
                                    {{ $item['daily_profit'] }} PKR
                                </span>
                                <br>
                                <small>Daily Interest</small>
                            </div>
                            <div style="float: right; text-align: center;">
                                <span style="font-size: 24px; font-weight: bold;">
                                    {{ $item['plan_days'] }}
                                </span>
                                <br>
                                <small>Plan Days</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <div class="row my-3">
                            <div class="col-6">
                                Initial Deposit:
                            </div>
                            <div class="col-6 text-right">
                                {{ $item['amount'] }} PKR
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-6">
                                Daily Interest:
                            </div>
                            <div class="col-6 text-right">
                                {{ $item['daily_profit'] }} PKR
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-6">
                                Plan Duration:
                            </div>
                            <div class="col-6 text-right">
                                {{ $item['plan_days'] }} PKR
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-6">
                                Total Return:
                            </div>
                            <div class="col-6 text-right">
                                {{ $item['total_profit'] }} PKR
                            </div>
                        </div>
                        <div class="row my-3 text-center">
                            <div class="col-12">
                                <form action="{{route('userSubscriptions.store')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user" value="{{Auth::user()['id']}}">
                                    <button class="btn btn-primary" name="invest" value="{{ $item['id'] }}">
                                        Invest Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
