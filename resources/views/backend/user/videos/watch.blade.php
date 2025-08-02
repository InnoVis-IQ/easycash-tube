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
    
    .daily-reward-card {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .reward-amount {
        font-size: 2rem;
        font-weight: bold;
    }
    
    .video-card {
        transition: transform 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .video-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .progress-container {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    /* Mobile specific styles */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .daily-reward-card {
            padding: 12px;
        }
        
        .reward-amount {
            font-size: 1.25rem;
        }
        
        .progress-container {
            padding: 12px;
        }
        
        .video-card {
            margin-bottom: 15px;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        

    }
    
    @media (max-width: 576px) {
        .col-md-4 {
            margin-bottom: 15px;
        }
        
        .d-flex.justify-content-center {
            flex-direction: column;
        }
        
        .d-flex.justify-content-center .btn {
            margin-bottom: 0.5rem;
        }
        
        .reward-amount {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2">
    <!-- Daily Reward Section -->
    <div class="daily-reward-card">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <h5><i class="fa-solid fa-gift me-2"></i>Daily Reward</h5>
                <p class="mb-2 small">Watch a video today to earn your daily reward!</p>
                @if($dailyRewardClaimed)
                    <div class="alert alert-success mb-0">
                        <i class="fa-solid fa-check-circle me-2"></i>
                        Daily reward claimed! Come back tomorrow for more rewards.
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fa-solid fa-play-circle me-2"></i>
                        Click on any video below to start watching and earn Rs{{ $dailyRewardAmount }}!
                    </div>
                @endif
            </div>
            <div class="col-12 col-md-4 text-center">
                <div class="reward-amount">Rs{{ $dailyRewardAmount }}</div>
                <small>Daily Reward</small>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="progress-container">
        <h6><i class="fa-solid fa-chart-line me-2"></i>Your Progress</h6>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" 
                 style="width: {{ $totalPlanDays > 0 ? round(($daysWatched/$totalPlanDays)*100) : 0 }}%" 
                 aria-valuenow="{{ $daysWatched }}" aria-valuemin="0" aria-valuemax="{{ $totalPlanDays }}">
                                 <small>{{ $daysWatched }} / {{ $totalPlanDays }} days watched</small>
            </div>
        </div>
        <small class="text-muted">Keep watching to earn more rewards!</small>
    </div>

    @if($watchedToday || $dailyRewardClaimed)
        <div class="alert alert-success">
            <i class="fa-solid fa-check-circle me-2"></i>
            You have already watched your video for today. Come back tomorrow for the next one!
        </div>
    @else
        <h5><i class="fa-solid fa-play-circle me-2"></i>Available Videos</h5>
        <div class="row">
            @php
                $unWatched = $videos->whereNotIn('id', $watchedVideos);
            @endphp
            @forelse($unWatched as $video)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                    <div class="card h-100 video-card"
                         data-title="{{ $video->title }}"
                         data-url="{{ $video->video_url }}"
                         data-video-id="{{ $video->id }}"
                         style="cursor:pointer;">
                        @if($video->video_thumbnail)
                            <img src="{{ asset($video->video_thumbnail) }}" class="card-img-top" alt="Thumbnail" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="d-flex justify-content-center align-items-center" style="height:200px;background:#f5f5f5;">
                                <img src="{{ asset('assets/images/product_images/watch.jpeg') }}" alt="Plan Icon" style="height:80px;">
                            </div>
                        @endif
                        <div class="card-body p-3">
                            <h6 class="card-title small">{{ $video->title }}</h6>
                            <p class="card-text small">{{ $video->description }}</p>
                            <span class="badge bg-warning small">Click to Watch</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        No videos available for your plan at the moment.
                    </div>
                </div>
            @endforelse
        </div>
    @endif

    @if(count($watchedVideos) > 0)
        <h3 class="mt-5"><i class="fa-solid fa-history me-2"></i>Watched Videos</h3>
        <div class="row">
            @php
                $watched = $videos->whereIn('id', $watchedVideos);
            @endphp
            @forelse($watched as $video)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 border-success video-card"
                         data-title="{{ $video->title }}"
                         data-url="{{ $video->video_url }}"
                         data-video-id="{{ $video->id }}"
                         style="cursor:pointer;">
                        @if($video->video_thumbnail)
                            <img src="{{ asset($video->video_thumbnail) }}" class="card-img-top" alt="Thumbnail" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="d-flex justify-content-center align-items-center" style="height:200px;background:#f5f5f5;">
                                <img src="{{ asset('assets/images/product_images/watch.jpeg') }}" alt="Plan Icon" style="height:80px;">
                            </div>
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $video->title }}</h6>
                            <p class="card-text small">{{ $video->description }}</p>
                            <span class="badge bg-success">Watched</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">You have not watched any videos yet.</p>
                </div>
            @endforelse
        </div>
    @endif
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
            
            // Show success message with reward amount
            showRewardNotification(data.daily_reward);
            
            console.log('Video marked as watched:', data.message);
        } else {
            console.error('Error marking video as watched:', data.message);
            showErrorNotification(data.message);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showErrorNotification('An error occurred while processing your request.');
    });
}

function updateVideoCardStatus(videoCard, isWatched) {
    if (isWatched) {
        // Update badge to show watched status
        var badge = videoCard.querySelector('.badge');
        if (badge) {
            badge.className = 'badge bg-success small';
            badge.textContent = 'Watched';
        }
        
        // Add border-success class to card
        videoCard.classList.add('border-success');
    }
}

function showRewardNotification(rewardAmount) {
    // Create a custom notification
    var notification = document.createElement('div');
    notification.className = 'alert alert-success position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 90vw;';
    notification.innerHTML = `
        <i class="fa-solid fa-gift me-2"></i>
        <strong>Daily Reward Claimed!</strong><br>
                 <small>You earned Rs${rewardAmount} for watching today's video!</small>
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function showErrorNotification(message) {
    // Create a custom error notification
    var notification = document.createElement('div');
    notification.className = 'alert alert-danger position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 90vw;';
    notification.innerHTML = `
        <i class="fa-solid fa-exclamation-triangle me-2"></i>
        <strong>Error!</strong><br>
                 <small>${message}</small>
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
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