@extends('layout.master-mini')
@section('content')
    <div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one">
        <div class="row w-100">
            <div class="col-lg-4 mx-auto">
                <div class="auto-form-wrapper">
                    <form method="POST" action="{{ route('login') }}">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @csrf
                        <div class="form-group">
                            <label class="label">Email/Phone Number</label>
                            <div class="input-group">
                                <input type="text" name="email" class="form-control" placeholder="Email/Contact">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                            </div>
                            <small>Phone number must be in the correct format [+923001234567]</small>
                        </div>
                        <div class="form-group">
                            <label class="label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" placeholder="*********">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary submit-btn btn-block">Login</button>
                        </div>
                        <div class="form-group d-flex justify-content-between">
                            {{-- <div class="form-check form-check-flat mt-0">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" checked> Keep me signed in </label>
                            </div> --}}
                            {{-- <a href="#" class="text-small forgot-password text-black">Forgot Password</a> --}}
                        </div>
                        {{-- <div class="form-group">
                            <button class="btn btn-block g-login">
                                <img class="mr-3" src="{{ url('assets/images/file-icons/icon-google.svg') }}"
                                    alt="">Log in with Google</button>
                        </div> --}}
                        <div class="text-block text-center my-3">
                            <span class="text-small font-weight-semibold">Not a member ?</span>
                            <a href="{{ route('register') }}" class="text-black text-small">Create new account</a>
                        </div>
                    </form>
                </div>
                <ul class="auth-footer">
                    <li>
                        <a href="#">Conditions</a>
                    </li>
                    <li>
                        <a href="#">Help</a>
                    </li>
                    <li>
                        <a href="#">Terms</a>
                    </li>
                </ul>
                <p class="footer-text text-center">copyright © 2018 Bootstrapdash. All rights reserved.</p>
            </div>
        </div>
    </div>
@endsection
