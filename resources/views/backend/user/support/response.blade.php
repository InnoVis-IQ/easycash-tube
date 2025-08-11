@extends('layout.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Support Request #{{ $supportRequest->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('support.history') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Support Request Details -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Your Request</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>Subject:</strong></div>
                                        <div class="col-md-9">{{ $supportRequest->subject }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>Category:</strong></div>
                                        <div class="col-md-9">
                                            <span class="badge badge-info">{{ ucfirst($supportRequest->category) }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>Status:</strong></div>
                                        <div class="col-md-9">
                                            @switch($supportRequest->status)
                                                @case('open')
                                                    <span class="badge badge-warning">Open</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge badge-primary">In Progress</span>
                                                    @break
                                                @case('resolved')
                                                    <span class="badge badge-success">Resolved</span>
                                                    @break
                                                @case('closed')
                                                    <span class="badge badge-secondary">Closed</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-light">{{ ucfirst($supportRequest->status) }}</span>
                                            @endswitch
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>Submitted:</strong></div>
                                        <div class="col-md-9">{{ $supportRequest->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>Your Message:</strong></div>
                                        <div class="col-md-9">
                                            <div class="border p-3 bg-light">
                                                {{ $supportRequest->message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Admin Response Section -->
                            @if($supportRequest->admin_response)
                                <div class="card mt-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-reply"></i> Admin Response
                                        </h5>
                                        <small class="text-light">Responded on {{ $supportRequest->admin_response_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="border p-3 bg-light">
                                            {{ $supportRequest->admin_response }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card mt-4">
                                    <div class="card-header bg-warning">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-clock"></i> Waiting for Response
                                        </h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <p class="text-muted">
                                            Your support request is being reviewed by our team. 
                                            We will respond to you as soon as possible.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <!-- Request Info -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Request Information</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Request ID:</strong> #{{ $supportRequest->id }}</p>
                                    <p><strong>Status:</strong> 
                                        @switch($supportRequest->status)
                                            @case('open')
                                                <span class="badge badge-warning">Open</span>
                                                @break
                                            @case('in_progress')
                                                <span class="badge badge-primary">In Progress</span>
                                                @break
                                            @case('resolved')
                                                <span class="badge badge-success">Resolved</span>
                                                @break
                                            @case('closed')
                                                <span class="badge badge-secondary">Closed</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">{{ ucfirst($supportRequest->status) }}</span>
                                        @endswitch
                                    </p>
                                    <p><strong>Category:</strong> {{ ucfirst($supportRequest->category) }}</p>
                                    <p><strong>Created:</strong> {{ $supportRequest->created_at->format('M d, Y') }}</p>
                                    @if($supportRequest->admin_response_at)
                                        <p><strong>Responded:</strong> {{ $supportRequest->admin_response_at->format('M d, Y') }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Actions</h5>
                                </div>
                                <div class="card-body">
                                                                         <a href="{{ route('support.index') }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-plus"></i> New Support Request
                                    </a>
                                                                         <a href="{{ route('support.history') }}" class="btn btn-secondary btn-block">
                                        <i class="fas fa-history"></i> View All Requests
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 