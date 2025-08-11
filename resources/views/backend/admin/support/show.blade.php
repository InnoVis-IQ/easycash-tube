@extends('layout.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Support Request #{{ $supportRequest->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.support.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
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
                                    <h5 class="card-title">Request Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>User:</strong></div>
                                        <div class="col-md-9">{{ $supportRequest->user->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>Email:</strong></div>
                                        <div class="col-md-9">{{ $supportRequest->user->email ?? 'N/A' }}</div>
                                    </div>
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
                                        <div class="col-md-3"><strong>Created:</strong></div>
                                        <div class="col-md-9">{{ $supportRequest->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3"><strong>Message:</strong></div>
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
                                    <div class="card-header">
                                        <h5 class="card-title">Admin Response</h5>
                                        <small class="text-muted">Responded on {{ $supportRequest->admin_response_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="border p-3 bg-light">
                                            {{ $supportRequest->admin_response }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Response Form -->
                            @if($supportRequest->status !== 'closed')
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5 class="card-title">Respond to Request</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.support.respond', $supportRequest->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="admin_response">Response</label>
                                                <textarea name="admin_response" id="admin_response" rows="5" 
                                                          class="form-control @error('admin_response') is-invalid @enderror" 
                                                          placeholder="Enter your response..." required>{{ old('admin_response') }}</textarea>
                                                @error('admin_response')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-paper-plane"></i> Send Response
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <!-- Status Update -->
                            @if($supportRequest->status !== 'closed')
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Update Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.support.update-status', $supportRequest->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="open" {{ $supportRequest->status === 'open' ? 'selected' : '' }}>Open</option>
                                                    <option value="in_progress" {{ $supportRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="resolved" {{ $supportRequest->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                    <option value="closed" {{ $supportRequest->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-save"></i> Update Status
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.support.close', $supportRequest->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary btn-block" 
                                                    onclick="return confirm('Are you sure you want to close this request?')">
                                                <i class="fas fa-times"></i> Close Request
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 