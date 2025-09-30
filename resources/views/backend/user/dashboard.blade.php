@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

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
    
    /* Disable video seeking controls */
    .custom-video-modal iframe {
        pointer-events: auto;
    }
    
    /* Add warning overlay */
    .video-warning-overlay {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        background: rgba(255, 193, 7, 0.9);
        color: #000;
        padding: 10px;
        border-radius: 5px;
        font-size: 12px;
        z-index: 1000;
        text-align: center;
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
    
    .video-card {
        transition: transform 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .video-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    /* Mobile specific styles */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
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
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2">
    @if($subscription_status == 'no')
        <!-- Wallet Balance -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card card-body text-center">
                    <h5><i class="fa-solid fa-wallet me-2"></i>Wallet Balance</h5>
                    <h3>0 PKR</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-body text-center">
                    <h5><i class="fa-solid fa-play-circle me-2"></i>Active Plans</h5>
                    <h3>0</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-body text-center">
                    <h5><i class="fa-solid fa-compass-drafting me-2"></i>Available Plans</h5>
                    <h3>{{ $allPlans->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 text-center">
                <a href="{{ route('plans.index') }}" class="btn btn-primary btn-lg" style="border-radius: 8px;">
                    <i class="fa-solid fa-plus-circle me-2"></i>Buy a plan to start earning
                </a>
            </div>
        </div>
    @else
        <!-- Notification Banners -->
        @if($announcements && count($announcements) > 0)
        <div class="row mb-3">
            <div class="col-12">
                @foreach($announcements as $announcement)
                    <div class="alert alert-{{ $announcement->type }} alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid {{ $announcement->type == 'info' ? '#17a2b8' : ($announcement->type == 'warning' ? '#ffc107' : ($announcement->type == 'success' ? '#28a745' : '#dc3545')) }};">
                        <div class="d-flex align-items-start">
                            <div class="me-3 mt-1">
                                @if($announcement->type == 'info')
                                    <i class="fa-solid fa-info-circle fa-lg text-info"></i>
                                @elseif($announcement->type == 'warning')
                                    <i class="fa-solid fa-exclamation-triangle fa-lg text-warning"></i>
                                @elseif($announcement->type == 'success')
                                    <i class="fa-solid fa-check-circle fa-lg text-success"></i>
                                @elseif($announcement->type == 'danger')
                                    <i class="fa-solid fa-times-circle fa-lg text-danger"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="alert-heading mb-1 fw-bold">{{ $announcement->title }}</h6>
                                        <p class="mb-1">{{ $announcement->message }}</p>
                                        <small class="text-muted">
                                            <i class="fa-solid fa-clock me-1"></i>
                                            Posted on {{ $announcement->created_at->format('M d, Y \a\t g:i A') }}
                                        </small>
                                    </div>
                                    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- User Progress and Level Card -->
        <div class="row mb-3">
            <div class="col-12 col-lg-8 mb-3">
                <div class="card bg-dark text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success rounded-circle p-2 me-3" style="min-width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-user text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                            </div>
                        </div>
                        
                        <!-- Watched Videos Progress -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small">Current Plan Progress</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $watchedVideosProgress }}%" aria-valuenow="{{ $watchedVideosProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">{{ $daysWatched }} days watched</small>
                                <small class="text-muted">5 days required</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Earnings and Activity Metrics -->
            <div class="col-12 col-lg-4">
                <div class="card bg-light">
                    <div class="card-body p-3">
                        <h5 class="text-center mb-3 small">FINANCIAL SUMMARY</h5>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-center">
                                    <i class="fa-solid fa-chart-line fa-lg text-primary mb-1"></i>
                                    <div class="small"><strong>EARNED</strong></div>
                                    <div class="small text-primary">Rs{{ number_format($totalEarned) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <i class="fa-solid fa-gift fa-lg text-warning mb-1"></i>
                                    <div class="small"><strong>TODAY'S REWARD</strong></div>
                                    <div class="small text-warning">Rs{{ number_format($todayReward) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <i class="fa-solid fa-arrow-down fa-lg text-success mb-1"></i>
                                    <div class="small"><strong>DEPOSITS</strong></div>
                                    <div class="small text-success">Rs{{ number_format($totalDeposits) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <i class="fa-solid fa-arrow-up fa-lg text-danger mb-1"></i>
                                    <div class="small"><strong>WITHDRAWALS</strong></div>
                                    <div class="small text-danger">Rs{{ number_format($totalWithdrawals) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-2">
                        
                        <div class="text-center">
                            <h6 class="mb-1">WALLET BALANCE</h6>
                            <h5 class="text-success mb-0">Rs{{ number_format($walletBalance) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Invite & Earn Section -->
        <div class="row mb-3">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body text-center p-3">
                        <h6 class="mb-2">Daily Reward</h6>
                        <div class="mb-2">
                            <i class="fa-solid fa-play-circle fa-2x text-primary"></i>
                        </div>
                        <h6 class="small">Watch Video</h6>
                        <p class="text-muted small mb-2">Complete daily video to earn rewards</p>
                        <a href="{{ route('videos.watch') }}" class="btn btn-primary btn-sm">Watch Now</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Invite & Earn Section -->
        <div class="row mb-3">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body text-center p-3">
                        <h6 class="mb-2">Bonus Video</h6>
                        <p class="text-muted small mb-2">Completely watch to earn bonus</p>
                        <div class="card h-100 video-card"
                            data-title="Bonus Video"
                            data-url="https://www.youtube.com/watch?v=HHjSdy9l7Kc"
                            data-video-id="dQw4w9WgXcQ"
                            style="cursor:pointer;">

                            <div class="d-flex justify-content-center align-items-center" style="height:200px;background:#f5f5f5;">
                                <img src="{{ asset('assets/images/product_images/watch.jpeg') }}" alt="Plan Icon" style="height:80px;">
                            </div>

                            <div class="card-body p-3">
                                <h6 class="card-title small">Bonus Video</h6>
                                <p class="card-text small">Completely watch to earn bonus</p>
                                <span class="badge bg-warning small">Click to Watch</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Referral Section -->
        <div class="row mb-3">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body text-center p-3">
                        <h6 class="mb-2"><i class="fa-solid fa-share-alt me-2"></i>Referral System</h6>
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="referralCode" value="{{ Auth::user()->referral_code }}" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyReferralCode()">
                                    <i class="fa-solid fa-copy"></i>
                                </button>
                            </div>
                            <small class="text-muted">Share this code with friends to earn Rs. 100 bonus</small>
                        </div>
                        <div class="row text-center">
                            <div class="col-6">
                                <h6 class="text-primary">{{ $referralCount }}</h6>
                                <small class="text-muted">Referrals</small>
                            </div>
                            <div class="col-6">
                                <h6 class="text-success">Rs. 100</h6>
                                <small class="text-muted">Per Referral</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body p-3">
                        <h6 class="mb-2"><i class="fa-solid fa-info-circle me-2"></i>How Referrals Work</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>Share your referral code with friends</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>When they sign up using your code</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>And buy any plan</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>You earn Rs. 100 bonus instantly!</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lucky Withdraw Section -->
        <div class="row mb-3">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body text-center p-3">
                        <h6 class="mb-2"><i class="fa-solid fa-gift me-2"></i>Lucky Withdraw Contest</h6>
                        <div class="mb-3">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h6 class="text-primary">Entry Fee</h6>
                                    <p class="mb-0">Rs. 100</p>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-success">Prize</h6>
                                    <p class="mb-0">Plan 3 (Rs. 7,000)</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small mb-3">Deposit Rs. 100 for a chance to win Plan 3 absolutely FREE!</p>
                        <a href="{{ route('lucky-withdraw.index') }}" class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-ticket me-2"></i>Enter Contest
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body p-3">
                        <h6 class="mb-2"><i class="fa-solid fa-info-circle me-2"></i>How Lucky Withdraw Works</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>Deposit Rs. 100 via EasyPaisa/JazzCash</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>Submit your entry with transaction ID</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>Wait for admin approval</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>Every 15 days, one winner is selected!</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Current Plans Section -->
        @if($subscriptions && count($subscriptions) > 0)
        <div class="card mb-3">
            <div class="card-header p-3">
                <h6 class="mb-0">Current Plan</h6>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    @foreach ($subscriptions as $subscription)
                        @php
                            $remainingDays = (strtotime(date('Y-m-d', strtotime($subscription['expire_at']))) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
                        @endphp
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-header bg-primary text-center p-2">
                                    <strong class="small">{{ $subscription['plan']['title'] ?? 'Plan' }}</strong>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row mb-1">
                                        <div class="col-7 small">Daily Profit:</div>
                                        <div class="col-5 text-end small">{{ $subscription['plan']['daily_profit'] ?? 0 }} PKR</div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-7 small">Profit Earned:</div>
                                        <div class="col-5 text-end small">{{ $subscription['earning'] }} PKR</div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-7 small">Ending in:</div>
                                        <div class="col-5 text-end small">{{ $remainingDays }} days</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-7 small">Total Profit:</div>
                                        <div class="col-5 text-end small">{{ $subscription['plan']['total_profit'] ?? 0 }} PKR</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
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
        <div class="video-warning-overlay">
            <i class="fa-solid fa-exclamation-triangle me-1"></i>
            <strong>Important:</strong> Please watch the entire video without skipping to earn your bonus reward.
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// YouTube iframe API
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;
var currentVideoId;
var currentVideoCard;
var lastPlayTime = 0;
var isSeeking = false;
var seekAttempts = 0;
var totalWatchTime = 0;
var videoStartTime = 0;

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
    
    // Reset tracking variables for new video
    lastPlayTime = 0;
    isSeeking = false;
    seekAttempts = 0;
    totalWatchTime = 0;
    videoStartTime = 0;

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
            'modestbranding': 1,
            'disablekb': 1, // Disable keyboard controls
            'fs': 0 // Disable fullscreen button
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
    
    // Disable right-click context menu on the video player
    var iframe = document.getElementById('videoUrl');
    if (iframe && iframe.querySelector('iframe')) {
        var youtubeIframe = iframe.querySelector('iframe');
        youtubeIframe.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });
    }
}

function onPlayerStateChange(event) {
    console.log('Player state changed:', event.data);
    
    // Check if video has ended (state 0 = ended)
    if (event.data === YT.PlayerState.ENDED) {
        console.log('Video ended, marking as watched for video ID:', currentVideoId);
        markVideoAsWatched(currentVideoId, currentVideoCard);
    }
    
    // Monitor for seeking attempts
    if (event.data === YT.PlayerState.PLAYING) {
        if (videoStartTime === 0) {
            videoStartTime = Date.now();
        }
        startProgressMonitoring();
    }
    
    // Track pause time
    if (event.data === YT.PlayerState.PAUSED) {
        // Don't count pause time towards total watch time
    }
}

function startProgressMonitoring() {
    if (!player) return;
    
    setInterval(function() {
        if (player && player.getCurrentTime) {
            var currentTime = player.getCurrentTime();
            
            // Check if user tried to seek forward
            if (currentTime > lastPlayTime + 5) { // Allow 5 seconds forward tolerance
                console.log('Seek attempt detected! Current time:', currentTime, 'Last time:', lastPlayTime);
                seekAttempts++;
                
                // Reset to last valid position
                player.seekTo(lastPlayTime, true);
                
                // Show warning after first attempt
                if (seekAttempts === 1) {
                    showSeekWarning();
                }
                
                // After multiple attempts, consider invalidating the watch
                if (seekAttempts >= 3) {
                    showSeekError();
                    return;
                }
            } else {
                // Update last play time if it's a reasonable progression
                if (currentTime >= lastPlayTime) {
                    lastPlayTime = currentTime;
                    totalWatchTime = currentTime;
                }
            }
        }
    }, 1000); // Check every second
}

function showSeekWarning() {
    var notification = document.createElement('div');
    notification.className = 'alert alert-warning position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 90vw;';
    notification.innerHTML = `
        <i class="fa-solid fa-exclamation-triangle me-2"></i>
        <strong>Warning!</strong><br>
        <small>Please watch the video without skipping. Skipping may invalidate your reward.</small>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 4000);
}

function showSeekError() {
    var notification = document.createElement('div');
    notification.className = 'alert alert-danger position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 90vw;';
    notification.innerHTML = `
        <i class="fa-solid fa-ban me-2"></i>
        <strong>Video Skipping Detected!</strong><br>
        <small>Multiple skip attempts detected. This video may not count for your bonus reward.</small>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function markVideoAsWatched(videoId, videoCard) {
    console.log('Attempting to mark video as watched:', videoId);
    
    // Validate watch time before marking as watched
    if (player && player.getDuration) {
        var videoDuration = player.getDuration();
        var watchPercentage = (totalWatchTime / videoDuration) * 100;
        
        console.log('Video duration:', videoDuration, 'Watch time:', totalWatchTime, 'Percentage:', watchPercentage);
        
        // Require at least 80% of the video to be watched
        if (watchPercentage < 80) {
            showErrorNotification('You must watch at least 80% of the video to earn your bonus reward. Please watch the complete video.');
            return;
        }
        
        // Check for excessive seeking
        if (seekAttempts >= 3) {
            showErrorNotification('Too many skip attempts detected. Please watch the video without skipping to earn your bonus reward.');
            return;
        }
    }
    
    // Get CSRF token
    var csrfToken = document.querySelector('meta[name="_token"]').getAttribute('content');
    console.log('CSRF Token:', csrfToken);
    
    // Send AJAX request to mark video as watched
    fetch('{{ route('dashboard.mark-watched') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            video_id: videoId,
            watch_time: totalWatchTime,
            video_duration: player && player.getDuration ? player.getDuration() : 0,
            seek_attempts: seekAttempts
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
            showRewardNotification(data.bonus_reward);
            
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
        <strong>Bonus Reward Claimed!</strong><br>
        <small>You earned Rs${rewardAmount} for watching the bonus video!</small>
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

function copyReferralLink() {
    const linkInput = document.querySelector('input[readonly]');
    linkInput.select();
    document.execCommand('copy');
    
    // Show feedback
    const copyBtn = event.target;
    const originalText = copyBtn.textContent;
    copyBtn.textContent = 'COPIED!';
    copyBtn.classList.add('btn-success');
    copyBtn.classList.remove('btn-light');
    
    setTimeout(() => {
        copyBtn.textContent = originalText;
        copyBtn.classList.remove('btn-success');
        copyBtn.classList.add('btn-light');
    }, 2000);
}

function copyReferralCode() {
    const referralInput = document.getElementById('referralCode');
    referralInput.select();
    referralInput.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        
        // Show feedback
        const copyBtn = event.target;
        const originalIcon = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="fa-solid fa-check"></i>';
        copyBtn.classList.add('btn-success');
        copyBtn.classList.remove('btn-outline-secondary');
        
        setTimeout(() => {
            copyBtn.innerHTML = originalIcon;
            copyBtn.classList.remove('btn-success');
            copyBtn.classList.add('btn-outline-secondary');
        }, 2000);
        
        // Show toast notification
        showToast('Referral code copied to clipboard!', 'success');
    } catch (err) {
        console.error('Failed to copy: ', err);
        showToast('Failed to copy referral code', 'error');
    }
}

// Auto-dismiss notifications after 10 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert && alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 10000); // 10 seconds
    });
});
</script>

<style>
.alert {
    border-radius: 8px;
    margin-bottom: 1rem;
}

.alert-dismissible .btn-close {
    padding: 0.75rem 1rem;
}

.alert-heading {
    font-size: 1.1rem;
    font-weight: 600;
}

.alert p {
    font-size: 0.95rem;
    line-height: 1.4;
}

.alert small {
    font-size: 0.8rem;
}
</style>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/dashboard.js') !!}
@endpush
