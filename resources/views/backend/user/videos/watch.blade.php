@extends('layout.master')

@section('content')
<div class="container mt-4">
    <h2>Available Videos</h2>
    <div class="row">
        @forelse($videos as $video)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($video->video_thumbnail)
                        <img src="{{ asset($video->video_thumbnail) }}" class="card-img-top" alt="Thumbnail">
                    @else
                        <div class="d-flex justify-content-center align-items-center" style="height:200px;background:#f5f5f5;">
                            <img src="{{ asset('assets/images/product_images/watch.jpeg') }}" alt="Plan Icon" style="height:80px;">
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $video->title }}</h5>
                        <p class="card-text">{{ $video->description }}</p>
                        @if(in_array($video->id, $watchedVideos))
                            <span class="badge bg-success">Watched</span>
                        @else
                            <span class="badge bg-secondary">Not Watched</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>No videos available for your plan.</p>
            </div>
        @endforelse
    </div>

    <h2 class="mt-5">Watched Videos</h2>
    <div class="row">
        @php
            $watched = $videos->whereIn('id', $watchedVideos);
        @endphp
        @forelse($watched as $video)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-success">
                    @if($video->video_thumbnail)
                        <img src="{{ asset($video->video_thumbnail) }}" class="card-img-top" alt="Thumbnail">
                    @else
                        <div class="d-flex justify-content-center align-items-center" style="height:200px;background:#f5f5f5;">
                            <img src="{{ asset('assets/images/product_images/watch.jpeg') }}" alt="Plan Icon" style="height:80px;">
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $video->title }}</h5>
                        <p class="card-text">{{ $video->description }}</p>
                        <span class="badge bg-success">Watched</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>You have not watched any videos yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection 