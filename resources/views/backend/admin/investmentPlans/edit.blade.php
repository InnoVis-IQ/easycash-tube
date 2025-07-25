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

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 style="font-weight: bold;">Edit Investment Plan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('plans.update', $investmentPlan->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group mb-3">
                            <label for="title">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $investmentPlan->title) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $investmentPlan->description) }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="amount">Amount *</label>
                            <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $investmentPlan->amount) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="daily_profit">Daily Profit *</label>
                            <input type="number" class="form-control" id="daily_profit" name="daily_profit" value="{{ old('daily_profit', $investmentPlan->daily_profit) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="plan_days">Plan Days *</label>
                            <input type="number" class="form-control" id="plan_days" name="plan_days" value="{{ old('plan_days', $investmentPlan->plan_days) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="buffer_days">Buffer Days</label>
                            <input type="number" class="form-control" id="buffer_days" name="buffer_days" value="{{ old('buffer_days', $investmentPlan->buffer_days) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="total_profit">Total Profit</label>
                            <input type="number" class="form-control" id="total_profit" name="total_profit" value="{{ old('total_profit', $investmentPlan->total_profit) }}" disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label for="limit">Limit</label>
                            <input type="text" class="form-control" id="limit" name="limit" value="{{ old('limit', $investmentPlan->limit) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" {{ old('status', $investmentPlan->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $investmentPlan->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Plan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    function updateTotalProfit() {
        const dailyProfit = parseFloat(document.getElementById('daily_profit').value) || 0;
        const planDays = parseFloat(document.getElementById('plan_days').value) || 0;
        document.getElementById('total_profit').value = dailyProfit * planDays;
    }
    document.getElementById('daily_profit').addEventListener('input', updateTotalProfit);
    document.getElementById('plan_days').addEventListener('input', updateTotalProfit);
    // Initial calculation
    updateTotalProfit();
</script>
@endpush 