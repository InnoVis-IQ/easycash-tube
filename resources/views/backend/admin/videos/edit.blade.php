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
                    <h4 style="font-weight: bold;">Edit Video</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route($route[0].'.update', $video->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group mb-3">
                            <label for="plan_id">Investment Plan *</label>
                            <select class="form-control" id="plan_id" name="plan_id" required>
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id', $video->plan_id) == $plan->id ? 'selected' : '' }}>{{ $plan->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $video->title) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $video->description) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="video_url">Video URL *</label>
                            <input type="text" class="form-control" id="video_url" name="video_url" value="{{ old('video_url', $video->video_url) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="video_thumbnail">Video Thumbnail</label>
                            <input type="text" class="form-control" id="video_thumbnail" name="video_thumbnail" value="{{ old('video_thumbnail', $video->video_thumbnail) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $video->expiry_date ? \Carbon\Carbon::parse($video->expiry_date)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" {{ old('status', $video->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $video->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Video</button>
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