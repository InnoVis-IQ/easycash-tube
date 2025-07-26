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
        @php
            $unWatched = $videos->whereNotIn('id', $watchedVideos);
        @endphp
        @forelse($unWatched as $video)
            <div class="col-md-4 mb-4">
                <div class="card h-100 video-card"
                     data-title="{{ $video->title }}"
                     data-url="{{ $video->video_url }}"
                     data-video-id="{{ $video->id }}"
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
                     data-video-id="{{ $video->id }}"
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
        <div id="videoUrl" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10;"></div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
// YouTube iframe API
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;
var currentVideoId;
var currentVideoCard;

function onYouTubeIframeAPIReady() {
    // This function will be called when the YouTube API is ready
}

function createYouTubePlayer(videoUrl, videoId, videoCard) {
    console.log('Creating YouTube player for:', videoUrl, 'Video ID:', videoId);
    
    // Extract video ID from YouTube URL
    var videoIdFromUrl = extractVideoId(videoUrl);
    if (!videoIdFromUrl) {
        console.error('Invalid YouTube URL:', videoUrl);
        return;
    }
    
    console.log('Extracted YouTube video ID:', videoIdFromUrl);

    currentVideoId = videoId;
    currentVideoCard = videoCard;

    // Create YouTube player
    player = new YT.Player('videoUrl', {
        height: '100%',
        width: '100%',
        videoId: videoIdFromUrl,
        playerVars: {
            'autoplay': 1,
            'controls': 1,
            'rel': 0,
            'showinfo': 0,
            'modestbranding': 1
        },
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });
    
    console.log('YouTube player created');
}

function extractVideoId(url) {
    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    var match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

function onPlayerReady(event) {
    console.log('YouTube player is ready');
}

function onPlayerStateChange(event) {
    console.log('Player state changed:', event.data);
    // Check if video has ended (state 0 = ended)
    if (event.data === YT.PlayerState.ENDED) {
        console.log('Video ended, marking as watched for video ID:', currentVideoId);
        markVideoAsWatched(currentVideoId, currentVideoCard);
    }
}

function markVideoAsWatched(videoId, videoCard) {
    console.log('Attempting to mark video as watched:', videoId);
    
    // Get CSRF token
    var csrfToken = document.querySelector('meta[name="_token"]').getAttribute('content');
    console.log('CSRF Token:', csrfToken);
    
    // Send AJAX request to mark video as watched
    fetch('{{ route("videos.mark-watched") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            video_id: videoId
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Update the UI to show video as watched
            updateVideoCardStatus(videoCard, true);
            console.log('Video marked as watched:', data.message);
        } else {
            console.error('Error marking video as watched:', data.message);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });
}

function updateVideoCardStatus(videoCard, isWatched) {
    if (isWatched) {
        // Update badge to show watched status
        var badge = videoCard.querySelector('.badge');
        if (badge) {
            badge.className = 'badge bg-success';
            badge.textContent = 'Watched';
        }
        
        // Add border-success class to card
        videoCard.classList.add('border-success');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.video-card').forEach(function(card) {
        card.addEventListener('click', function() {
            var title = this.getAttribute('data-title');
            var url = this.getAttribute('data-url');
            var videoId = this.getAttribute('data-video-id');
            
            document.getElementById('videoModalLabel').textContent = title;
            
            // Clear previous iframe content
            var iframe = document.getElementById('videoUrl');
            iframe.innerHTML = '';
            
            // Create YouTube player
            createYouTubePlayer(url, videoId, this);
            
            var videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
            videoModal.show();
            
            // Add a manual button for testing (temporary)
            setTimeout(function() {
                if (document.querySelector('.manual-mark-btn')) {
                    document.querySelector('.manual-mark-btn').remove();
                }
                var manualBtn = document.createElement('button');
                manualBtn.className = 'btn btn-warning manual-mark-btn';
                manualBtn.textContent = 'Mark as Watched (Manual)';
                manualBtn.style.position = 'absolute';
                manualBtn.style.top = '10px';
                manualBtn.style.right = '10px';
                manualBtn.style.zIndex = '1000';
                manualBtn.onclick = function() {
                    markVideoAsWatched(videoId, this.closest('.video-card'));
                };
                document.querySelector('.modal-content').appendChild(manualBtn);
            }, 2000);
        });
    });

    // Clean up player when modal is closed
    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
        if (player && typeof player.destroy === 'function') {
            player.destroy();
        }
        // Clear the video container
        document.getElementById('videoUrl').innerHTML = '';
    });
});
</script>
@endpush
@endsection 