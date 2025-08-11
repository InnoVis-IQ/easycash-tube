@extends('layout.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Support Requests</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supportRequests as $request)
                                    <tr>
                                        <td>#{{ $request->id }}</td>
                                        <td>{{ $request->user->name ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($request->subject, 50) }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($request->category) }}</span>
                                        </td>
                                        <td>
                                            @switch($request->status)
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
                                                    <span class="badge badge-light">{{ ucfirst($request->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.support.show', $request->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            @if($request->status !== 'closed')
                                                <form action="{{ route('admin.support.close', $request->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-secondary" 
                                                            onclick="return confirm('Are you sure you want to close this request?')">
                                                        <i class="fas fa-times"></i> Close
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No support requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $supportRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 