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
                    <h4 style="font-weight: bold;">Create Video</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route($route[0].'.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="plan_id">Investment Plan *</label>
                            <select class="form-control" id="plan_id" name="plan_id" required>
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>
                        <div class="form-group mb-3">
                            <label for="video_url">Video URL *</label>
                            <input type="text" class="form-control" id="video_url" name="video_url" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="video_thumbnail">Video Thumbnail</label>
                            <input type="text" class="form-control" id="video_thumbnail" name="video_thumbnail">
                        </div>
                        <div class="form-group mb-3">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Video</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush 