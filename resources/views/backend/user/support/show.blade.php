@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Support Request Details</h2>
        <a href="{{ route('support.history') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to History
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $supportRequest->subject }}</h5>
                <span class="badge bg-{{ $supportRequest->status == 'open' ? 'warning' : ($supportRequest->status == 'closed' ? 'success' : 'info') }}">
                    {{ ucfirst($supportRequest->status) }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Category:</strong>
                    <span class="badge bg-info">{{ ucfirst($supportRequest->category) }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Priority:</strong>
                    <span class="badge bg-{{ $supportRequest->priority == 'high' ? 'danger' : ($supportRequest->priority == 'medium' ? 'warning' : 'success') }}">
                        {{ ucfirst($supportRequest->priority) }}
                    </span>
                </div>
                <div class="col-md-3">
                    <strong>Submitted:</strong>
                    {{ $supportRequest->created_at->format('M d, Y H:i') }}
                </div>
                <div class="col-md-3">
                    <strong>Ticket ID:</strong>
                    #{{ $supportRequest->id }}
                </div>
            </div>

            <div class="mb-4">
                <h6><strong>Your Message:</strong></h6>
                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($supportRequest->message)) !!}
                </div>
            </div>

            @if($supportRequest->admin_response)
                <div class="mb-4">
                    <h6><strong>Admin Response:</strong></h6>
                    <div class="border rounded p-3 bg-info text-white">
                        {!! nl2br(e($supportRequest->admin_response)) !!}
                        <div class="mt-2">
                            <small>Responded on: {{ $supportRequest->admin_response_at->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Your request is being reviewed by our support team. We will respond as soon as possible.
                </div>
            @endif

            <div class="text-center">
                <a href="{{ route('support.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Submit New Request
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush 