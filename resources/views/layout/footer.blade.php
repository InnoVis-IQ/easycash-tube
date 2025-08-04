
<!-- Bottom Navigation -->
<div class="fixed-bottom bg-white border-top" style="z-index: 1000; bottom: 0; left: 0; right: 0;">
  <div class="container-fluid px-2">
      <div class="text-center" style="width: 100%;">
          <div style="width: 20%; display: inline;">
              <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none p-2">
                  <i class="fa-solid fa-home fa-lg"></i>
                  <div class="small">Dashboard</div>
              </a>
          </div>
          <div style="width: 20%; display: inline;">
              <a href="{{ route('plans.index') }}" class="btn btn-link text-decoration-none p-2">
                  <i class="fa-solid fa-compass-drafting fa-lg"></i>
                  <div class="small">Plans</div>
              </a>
          </div>
          <div style="width: 20%; display: inline;">
            @if(Auth::user()->role == 'USER')
              <a href="{{ route('videos.watch') }}" class="btn btn-link text-decoration-none p-2">
                  <i class="fa-solid fa-play fa-lg"></i>
                  <div class="small">Videos</div>
              </a>
            @else
              <a href="{{ route('videos.index') }}" class="btn btn-link text-decoration-none p-2">
                  <i class="fa-solid fa-video fa-lg"></i>
                  <div class="small">Videos</div>
              </a>
            @endif
          </div>
          <div style="width: 20%; display: inline;">
              <a href="{{ route('withdraws.index') }}" class="btn btn-link text-decoration-none p-2">
                  <i class="fa-solid fa-arrow-up fa-lg"></i>
                  <div class="small">Withdraw</div>
              </a>
          </div>
          <div style="width: 20%; display: inline;">
              <a href="{{ route('deposits.index') }}" class="btn btn-link text-decoration-none p-2">
                  <i class="fa-solid fa-arrow-down fa-lg"></i>
                  <div class="small">Deposit</div>
              </a>
          </div>
      </div>
  </div>
</div>

<!-- Add margin to prevent content from being hidden behind bottom navigation -->
<div style="margin-bottom: 80px;"></div>

<style>
/* Ensure footer styles are properly applied */
.fixed-bottom {
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    background-color: #ffffff !important;
    border-top: 1px solid #dee2e6;
}

.btn-link {
    color: #6c757d !important;
    transition: color 0.3s ease;
    text-decoration: none !important;
}

.btn-link:hover {
    color: #007bff !important;
    text-decoration: none !important;
}

.btn-link:focus {
    box-shadow: none !important;
}

/* Mobile specific styles for footer */
@media (max-width: 768px) {
    .fixed-bottom .btn-link {
        padding: 0.5rem 0.25rem !important;
    }
    
    .fixed-bottom .small {
        font-size: 0.75rem !important;
    }
    
    .fixed-bottom .fa-lg {
        font-size: 1.25rem !important;
    }
}

@media (max-width: 576px) {
    .fixed-bottom .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
}
</style>
