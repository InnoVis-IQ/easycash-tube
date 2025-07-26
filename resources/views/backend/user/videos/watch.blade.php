@extends('layout.master')

@push('style')
<style>
    .custom-video-modal {
        max-width: 900px;
    }
    .custom-video-modal .modal-content,
    .custom-video-modal .modal-body {
        height: 600px;
        min-height: 400px !important;
    }
    .custom-video-modal .modal-content {
        border-radius: 10px;
    }
    .custom-video-modal .modal-header {
        border-bottom: none;
    }
    .custom-video-modal .modal-footer {
    }
    #videoUrl {
        height: 100% !important;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h2>Available Videos</h2>
    <div class="row">
        @forelse($videos as $video)
            <div class="col-md-4 mb-4">
                <div class="card h-100 video-card"
                     data-title="{{ $video->title }}"
                     data-url="{{ $video->video_url }}"
                     style="cursor:pointer;">
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
                <div class="card h-100 border-success video-card"
                     data-title="{{ $video->title }}"
                     data-url="{{ $video->video_url }}"
                     style="cursor:pointer;">
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

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg custom-video-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="videoModalLabel">Video Title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe id="videoUrl"
            src=""
            frameborder="0" allow="autoplay; fullscreen" allowfullscreen
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 10;">
        </iframe>
        <div class="video-wrapper">
            <a class="play-btn popup-youtube" href="https://www.youtube.com/watch?v=FrTz_dKhGIM">
                <i class="fas fa-play"></i>
            </a>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.video-card').forEach(function(card) {
        card.addEventListener('click', function() {
            var title = this.getAttribute('data-title');
            var url = this.getAttribute('data-url');
            document.getElementById('videoModalLabel').textContent = title;
            document.getElementById('videoUrl').setAttribute('src', url);
            var videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
            videoModal.show();
        });
    });
});
</script>
@endpush
@endsection 