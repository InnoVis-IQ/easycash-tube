@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
    <div class="card mb-3">
        <div class="card-header">
            Current Plans
        </div>
        <div class="card-body row">
            @foreach ($subscriptions as $subscription)
                @php
                    $remainingDays = (strtotime(date('Y-m-d', strtotime($subscription['expire_at']))) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
                @endphp
                <div class="col-md-4 col-12 p-3">
                    <div class=" card bg-info text-white p-0">
                        <div class="card-header bg-primary text-center p-0">
                            Investment Plan: {{ $subscription['plan']['title'] }}<br>
                        </div>
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-7 text-left">Daily Profit:</div>
                                <div class="col-5 text-right">{{ $subscription['plan']['daily_profit'] }} PKR</div>
                            </div>
                            <div class="row">
                                <div class="col-7 text-left">Profit Earned so far:</div>
                                <div class="col-5 text-right">{{ $subscription['earning'] }} PKR</div>
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
        <div class="card-body text-center">
            <a href="{{route('plans.create')}}" class="btn btn-primary"> Invest more</a>
        </div>
    </div>







    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
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
        <div class="col-md-4 grid-margin stretch-card">
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
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card card-statistics">
                <a href="">
                    <div class="card-body">
                        <div
                            class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="fa-solid fa-sack-dollar fa-2xl" style="color: #65440b;"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Upcoming Income</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $upcomingIncome }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            Upcoming Income in next 30 days
                        </p>
                    </div>
                </a>
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
