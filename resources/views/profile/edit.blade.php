@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
<div class="container mt-4">
    <h2>Account Settings</h2>
    
    @if(session('status') === 'profile-updated')
        <div class="alert alert-success">
            Profile information updated successfully.
        </div>
    @endif

    <!-- Profile Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h4><i class="fas fa-user"></i> Profile Information</h4>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('profile.update') }}" class="row">
                @csrf
                @method('patch')
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="name">Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="contact">Phone Number *</label>
                        <input type="text" class="form-control @error('contact') is-invalid @enderror" id="contact" name="contact" value="{{ old('contact', $user->contact) }}" required>
                        @error('contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="card mb-4">
        <div class="card-header">
            <h4><i class="fas fa-lock"></i> Change Password</h4>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('password.update') }}" class="row">
                @csrf
                @method('put')
                
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="current_password">Current Password *</label>
                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="password">New Password *</label>
                        <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" required>
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirm New Password *</label>
                        <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Login History -->
    <div class="card mb-4">
        <div class="card-header">
            <h4><i class="fas fa-history"></i> Login History</h4>
        </div>
        <div class="card-body">
            @if($loginHistory->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loginHistory as $login)
                                <tr>
                                    <td>{{ $login->created_at ?? 'N/A' }}</td>
                                    <td>{{ $login->ip_address ?? 'N/A' }}</td>
                                    <td>{{ $login->user_agent ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-success">Successful</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h5>No Login History</h5>
                    <p class="text-muted">Login history will appear here once you start using the system.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
