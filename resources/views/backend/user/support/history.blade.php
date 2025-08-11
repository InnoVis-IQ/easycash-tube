@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Support Request History</h2>
        <a href="{{ route('support.index') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Request
        </a>
    </div>

    @if($supportRequests->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Subject</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supportRequests as $request)
                                <tr>
                                    <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $request->subject }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($request->category) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $request->status == 'open' ? 'warning' : ($request->status == 'closed' ? 'success' : 'info') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('support.show', $request->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($request->admin_response)
                                            <a href="{{ route('support.response', $request->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-reply"></i> Response
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5>No Support Requests</h5>
                <p class="text-muted">You haven't submitted any support requests yet.</p>
                <a href="{{ route('support.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Submit Your First Request
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush 