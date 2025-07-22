@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
    <div class="row">
        <div class="col-10">
            <h5>Dashboard</h5>
        </div>
        <div class="col-2">
            <a href="{{ route('profitCalculation') }}" class="btn btn-primary">Run Today's Profit</a>
        </div>
    </div>
    @if (Session::has('message'))
        <div class="alert alert-info">
            {{ Session::get('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-6 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <a href="{{ route('users.index') }}">
                    <div class="card-body">
                        <div
                            class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="fa-solid fa-user-plus fa-2xl" style="color: #3267c3;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">New Users</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $newUsers }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            No. of new users in past 3 days
                        </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <a href="{{ route('deposits.index') }}">
                    <div class="card-body">
                        <div
                            class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="fa-solid fa-hand-holding-dollar fa-2xl" style="color: #048b45;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Pending Deposits</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $pendingDeposits }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            Deposits pending approcal
                        </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-money-check-dollar fa-2xl" style="color: #f0b7e1;"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Ongoing Subscriptions</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $activeSubscriptions }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        No. of on going Subscriptions
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="fa-solid fa-money-bill-transfer fa-2xl" style="color: #7de811;"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Invsted Amount</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $investedAmount }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        Amount invested in ongoing plans
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/dashboard.js') !!}
@endpush
