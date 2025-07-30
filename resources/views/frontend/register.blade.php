@extends('layout.master-mini')

@section('content')
<div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background: linear-gradient(135deg, #28a745 0%, #007bff 100%); min-height: 100vh;">
    <div class="row w-100">
        <div class="col-lg-4 mx-auto">
            <div class="auto-form-wrapper" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); padding: 50px 40px;">
                <!-- Logo/Brand Section -->
                <div class="text-center mb-4">
                    <h2 class="mb-3" style="color: #333; font-weight: 700; font-size: 2rem;">Create Account</h2>
                    <p class="text-muted" style="font-size: 0.95rem;">Join us and start your journey today</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger" style="border-radius: 12px; border: none; background: #fee; color: #c53030; padding: 15px; margin-bottom: 25px;">
                            <ul class="mb-0" style="padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group mb-4">
                        <label class="label mb-2" style="color: #555; font-weight: 600; font-size: 0.9rem;">Full Name</label>
                        <div class="input-group" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" 
                                   style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                            <div class="input-group-append">
                                <span class="input-group-text" style="border: none; background: #f8f9fa; color: #6c757d;">
                                    <i class="mdi mdi-account-outline"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="label mb-2" style="color: #555; font-weight: 600; font-size: 0.9rem;">Email Address</label>
                        <div class="input-group" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                            <input type="email" name="email" class="form-control" placeholder="Enter your email address" 
                                   style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                            <div class="input-group-append">
                                <span class="input-group-text" style="border: none; background: #f8f9fa; color: #6c757d;">
                                    <i class="mdi mdi-email-outline"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="label mb-2" style="color: #555; font-weight: 600; font-size: 0.9rem;">Phone Number</label>
                        <div class="input-group" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                            <input type="text" name="contact" value="+92" class="form-control" placeholder="Enter your phone number" 
                                   style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                            <div class="input-group-append">
                                <span class="input-group-text" style="border: none; background: #f8f9fa; color: #6c757d;">
                                    <i class="mdi mdi-phone-outline"></i>
                                </span>
                            </div>
                        </div>
                        <small class="text-muted" style="font-size: 0.8rem; margin-top: 8px; display: block;">
                            Enter contact number in proper format [+923001234567]
                        </small>
                    </div>

                    <div class="form-group mb-4">
                        <label class="label mb-2" style="color: #555; font-weight: 600; font-size: 0.9rem;">Password</label>
                        <div class="input-group" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                            <input type="password" name="password" class="form-control" placeholder="Create a strong password" 
                                   style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                            <div class="input-group-append">
                                <span class="input-group-text" style="border: none; background: #f8f9fa; color: #6c757d;">
                                    <i class="mdi mdi-lock-outline"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="label mb-2" style="color: #555; font-weight: 600; font-size: 0.9rem;">Confirm Password</label>
                        <div class="input-group" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm your password" 
                                   style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                            <div class="input-group-append">
                                <span class="input-group-text" style="border: none; background: #f8f9fa; color: #6c757d;">
                                    <i class="mdi mdi-lock-check-outline"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="referred_by" value="{{isset($_GET['ref']) ? $_GET['ref'] : ''}}">

                    <div class="form-group mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required style="border-radius: 4px;">
                            <label class="form-check-label text-muted" for="terms" style="font-size: 0.9rem;">
                                I agree to the                                 <a href="#" class="text-decoration-none" style="color: #28a745;">Terms of Service</a> and 
                                <a href="#" class="text-decoration-none" style="color: #28a745;">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <button class="btn btn-primary submit-btn btn-block" 
                                style="background: linear-gradient(135deg, #28a745 0%, #007bff 100%); border: none; border-radius: 12px; padding: 15px; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4); transition: all 0.3s ease;">
                            Create Account
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-decoration-none" style="color: #28a745; font-weight: 600;">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer Links -->
            <div class="text-center mt-4">
                <ul class="list-unstyled d-flex justify-content-center mb-2" style="gap: 20px;">
                    <li><a href="#" class="text-decoration-none" style="color: rgba(255, 255, 255, 0.8); font-size: 0.85rem;">Privacy Policy</a></li>
                    <li><a href="#" class="text-decoration-none" style="color: rgba(255, 255, 255, 0.8); font-size: 0.85rem;">Terms of Service</a></li>
                    <li><a href="#" class="text-decoration-none" style="color: rgba(255, 255, 255, 0.8); font-size: 0.85rem;">Help Center</a></li>
                </ul>
                <p class="text-muted mb-0" style="font-size: 0.8rem; color: rgba(255, 255, 255, 0.6);">
                    © 2024 EasyCash Tube. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.auth.theme-one .auto-form-wrapper {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(10px);
    border-radius: 20px !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    padding: 50px 40px !important;
}

.auth.theme-one .auto-form-wrapper .form-group .input-group {
    border-radius: 12px !important;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.auth.theme-one .auto-form-wrapper .form-group .input-group .form-control {
    border: none !important;
    padding: 15px 20px !important;
    font-size: 0.95rem !important;
    background: #f8f9fa !important;
    border-radius: 0 !important;
}

.auth.theme-one .auto-form-wrapper .form-group .input-group .input-group-append .input-group-text {
    border: none !important;
    background: #f8f9fa !important;
    color: #6c757d !important;
    border-radius: 0 !important;
}

.auth.theme-one .auto-form-wrapper .form-group .submit-btn {
    background: linear-gradient(135deg, #28a745 0%, #007bff 100%) !important;
    border: none !important;
    border-radius: 12px !important;
    padding: 15px !important;
    font-weight: 600 !important;
    font-size: 1rem !important;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4) !important;
    transition: all 0.3s ease !important;
}

.auth.theme-one .auto-form-wrapper .form-group .submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.6) !important;
}

.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
    border-color: #28a745 !important;
}

.form-check-input:checked {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
}
</style>
@endsection
