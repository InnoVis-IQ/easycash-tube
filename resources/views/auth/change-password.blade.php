@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
<div class="container-fluid px-2">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-lock me-2"></i>Change Password
                    </h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('password.update') }}" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Current Password -->
                        <div class="form-group mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-key me-1"></i>Current Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   placeholder="Enter your current password"
                                   required>
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- New Password -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>New Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter your new password"
                                   required>
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-check-circle me-1"></i>Confirm New Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Confirm your new password"
                                   required>
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Password
                            </button>
                        </div>
                        
                        <!-- Success Message -->
                        @if (session('status') === 'password-updated')
                            <div class="alert alert-success mt-3" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                Password updated successfully!
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('password_confirmation');
    if (confirmPassword.value) {
        if (this.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
});
</script>

<style>
.card {
    border-radius: 10px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 10px 10px 0 0 !important;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #ced4da;
    padding: 0.75rem 1rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.15s ease-in-out;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
}

.alert {
    border-radius: 8px;
    border: none;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}
</style>
@endsection

@push('plugin-scripts')
    {{-- Add any additional scripts here if needed --}}
@endpush

@push('custom-scripts')
    {{-- Add any custom scripts here if needed --}}
@endpush
