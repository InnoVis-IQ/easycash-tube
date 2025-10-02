@extends('layout.master-mini')

@section('content')
    <div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one"
        style="background: linear-gradient(135deg, #28a745 0%, #007bff 100%); min-height: 100vh;">
        <div class="row w-100">
            <div class="col-lg-4 mx-auto">
                <div class="auto-form-wrapper"
                    style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); padding: 50px 40px;">
                    <!-- Logo/Brand Section -->
                    <div class="text-center mb-4">
                        <h2 class="mb-3" style="color: #333; font-weight: 700; font-size: 2rem;">Create Account</h2>
                        <p class="text-muted" style="font-size: 0.95rem;">Join us and start your journey today</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger"
                                style="border-radius: 12px; border: none; background: #fee; color: #c53030; padding: 15px; margin-bottom: 25px;">
                                <ul class="mb-0" style="padding-left: 20px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group mb-4">
                            <label class="label mb-2" style="color: #555; font-weight: 600; font-size: 0.9rem;">Full
                                Name</label>
                            <div class="input-group"
                                style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                                <input type="text" name="name" class="form-control" placeholder="Enter your full name"
                                    style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                        style="border: none; background: #f8f9fa; color: #6c757d;">
                                        <i class="mdi mdi-account-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="label mb-2" style="color: #555; font-weight: 600; font-size: 0.9rem;">Email
                                Address</label>
                            <div class="input-group"
                                style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                                <input type="email" name="email" class="form-control"
                                    placeholder="Enter your email address"
                                    style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                        style="border: none; background: #f8f9fa; color: #6c757d;">
                                        <i class="mdi mdi-email-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="label mb-2" style="color: #555; font-weight: 600; font-size: 0.9rem;">Phone
                                Number</label>
                            <div class="input-group"
                                style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                                <input type="text" name="contact" value="+92" class="form-control"
                                    placeholder="Enter your phone number"
                                    style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                        style="border: none; background: #f8f9fa; color: #6c757d;">
                                        <i class="mdi mdi-phone-outline"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="text-muted" style="font-size: 0.8rem; margin-top: 8px; display: block;">
                                Enter contact number in proper format [+923001234567]
                            </small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="label mb-2"
                                style="color: #555; font-weight: 600; font-size: 0.9rem;">Password</label>
                            <div class="input-group"
                                style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                                <input type="password" name="password" class="form-control"
                                    placeholder="Create a strong password"
                                    style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                        style="border: none; background: #f8f9fa; color: #6c757d;">
                                        <i class="mdi mdi-lock-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="label mb-2" style="color: #555; font-weight: 600; font-size: 0.9rem;">Confirm
                                Password</label>
                            <div class="input-group"
                                style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Confirm your password"
                                    style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                        style="border: none; background: #f8f9fa; color: #6c757d;">
                                        <i class="mdi mdi-lock-check-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="label mb-2" style="color: #555; font-weight: 600; font-size: 0.9rem;">Referral Code
                                <span class="text-muted" style="font-size: 0.8rem;">(Optional)</span>
                            </label>
                            <div class="input-group"
                                style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                                <input type="text" name="referred_by" class="form-control"
                                    placeholder="Enter referral code (if any)"
                                    value="{{ isset($_GET['ref']) ? $_GET['ref'] : old('referred_by') }}"
                                    style="border: none; padding: 15px 20px; font-size: 0.95rem; background: #f8f9fa;">
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                        style="border: none; background: #f8f9fa; color: #6c757d;">
                                        <i class="mdi mdi-account-group-outline"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="text-muted" style="font-size: 0.8rem; margin-top: 8px; display: block;">
                                Enter the referral code of the person who referred you to earn bonus rewards
                            </small>
                        </div>

                        <div class="form-group mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required
                                    style="border-radius: 4px;">
                                <label class="form-check-label text-muted" for="terms" style="font-size: 0.9rem;">
                                    I agree to the <a href="#" class="text-decoration-none" style="color: #28a745;"
                                        data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Service</a>
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
                                <a href="{{ route('login') }}" class="text-decoration-none"
                                    style="color: #28a745; font-weight: 600;">
                                    Sign in here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Footer Links -->
                <div class="text-center mt-4">
                    <p class="text-muted mb-0" style="font-size: 0.8rem; color: rgba(255, 255, 255, 0.6);">
                        © 2024 EasyPay Tube. All rights reserved.
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

    <!-- Terms of Service Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content"
                style="border-radius: 15px; border: none; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #28a745 0%, #007bff 100%); color: white; border-radius: 15px 15px 0 0; border: none;">
                    <h5 class="modal-title" id="termsModalLabel" style="font-weight: 600;">
                        <i class="mdi mdi-file-document-outline me-2"></i>
                        Terms of Service
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 30px;">
                    <div class="terms-content" style="line-height: 1.6; color: #333;">
                        <h2>1. Acceptance of Terms</h2>
                        <p>By accessing or using the EasyPay Tube website and services, you agree to be bound by these
                            Terms and Conditions and our Privacy Policy. These Terms govern your access to and use of the
                            website, mobile apps, and all associated services. If you do not agree with any part of these
                            Terms, please refrain from using our services.</p>

                        <h2>2. Eligibility</h2>
                        <ul>
                            <li>Be at least 18 years of age or the legal age of majority in your jurisdiction.</li>
                            <li>Provide accurate and complete registration information.</li>
                            <li>Not be previously suspended or removed from the platform.</li>
                            <li>Comply with all local laws regarding online earnings and financial transactions.</li>
                        </ul>

                        <h2>3. Earning Disclaimer</h2>
                        <ul>
                            <li>Earnings are based on your selected plan and activity.</li>
                            <li>For example, a 7-day plan requires watching videos for at least 5 days to earn up to 10%
                                daily (up to 20% depending on the plan).</li>
                            <li>Earnings may fluctuate and are not guaranteed. EasyPay Tube does not promise fixed returns.
                            </li>
                            <li>Bonuses and promotions may be offered from time to time at the company’s discretion.</li>
                        </ul>

                        <h2>4. Account Usage & Security</h2>
                        <ul>
                            <li>Each user is allowed only one account. Duplicate or fake accounts will be permanently
                                banned.</li>
                            <li>It is your responsibility to maintain the confidentiality of your account and login
                                credentials.</li>
                            <li>You agree to notify us immediately of any unauthorized use of your account.</li>
                            <li>EasyPay Tube is not liable for any loss or damage from your failure to protect your
                                account.</li>
                        </ul>

                        <h2>5. Prohibited Activities</h2>
                        <p>Users must not:</p>
                        <ul>
                            <li>Use bots, scripts, or automated systems to access or interact with the platform.</li>
                            <li>Attempt to manipulate or abuse the earnings system.</li>
                            <li>Post or share false information or offensive content.</li>
                            <li>Impersonate another individual or entity.</li>
                            <li>Attempt unauthorized access to our servers or networks.</li>
                        </ul>
                        <p>Violation of these rules may result in suspension, account termination, and legal action.</p>

                        <h2>6. Content & Copyright</h2>
                        <ul>
                            <li>All videos, logos, text, graphics, and content on EasyPay Tube are protected by copyright
                                and intellectual property laws.</li>
                            <li>Users may not reproduce, modify, or redistribute any content without written permission.
                            </li>
                            <li>Content provided for earning purposes must not be shared outside the platform.</li>
                        </ul>

                        <h2>7. Modifications to Service</h2>
                        <ul>
                            <li>We reserve the right to change, suspend, or discontinue any part of the service at any time.
                            </li>
                            <li>We may also revise these Terms & Conditions. Any changes will be posted on this page with an
                                updated effective date.</li>
                            <li>Continued use of the service after changes constitutes your acceptance of the new terms.
                            </li>
                        </ul>

                        <h2>8. Payment & Withdrawals</h2>
                        <ul>
                            <li>Earnings can be withdrawn via Easypaisa, JazzCash, or bank transfer.</li>
                            <li><strong>Basic Plan (Rs. 500):</strong> There is no withdrawal option available for users on
                                the Basic Plan.</li>
                            <li>To be eligible for withdrawals, users must purchase at least the Rs. 2000 plan.</li>
                            <li>Minimum withdrawal amount may apply and is subject to change.</li>
                            <li>Processing times are typically within 24 hours, but delays may occur due to verification,
                                public holidays, or banking issues.</li>
                            <li>Users are solely responsible for the accuracy of their withdrawal information.</li>
                            <li>EasyPay Tube will not be liable for failed transactions due to incorrect details.</li>
                        </ul>

                        <p>If a user starts with the Rs. 500 Basic Plan, withdrawals are not allowed.</p>
                        <p>Once the user upgrades to the Rs. 2000 plan, they become eligible to withdraw only the earnings
                            from the previous Rs. 500 plan.</p>
                        <p>If the user then upgrades to the Rs. 6000 plan, they will be eligible to withdraw only the
                            earnings from the Rs. 2000 plan.</p>
                        <p>However, if the user upgrades directly to the Rs. 25,000 plan, they will become eligible to
                            withdraw the full accumulated earnings from all previous plans.</p>

                        <h2>9. Disclaimers</h2>
                        <ul>
                            <li>The EasyPay Tube platform is provided 'as is' and 'as available.'</li>
                            <li>We do not guarantee that the service will be uninterrupted, error-free, or secure.</li>
                            <li>We are not responsible for any delays, losses, or damages due to server issues, third-party
                                services, or user negligence.</li>
                        </ul>

                        <h2>10. Limitation of Liability</h2>
                        <p>To the fullest extent permitted by law:</p>
                        <ul>
                            <li>EasyPay Tube shall not be liable for any indirect, incidental, special, or consequential
                                damages.</li>
                            <li>This includes, but is not limited to, loss of profits, data, or goodwill arising from your
                                use of the platform.</li>
                        </ul>

                        <h2>11. Termination</h2>
                        <ul>
                            <li>We may suspend or terminate your access to the service at our discretion and without prior
                                notice if you violate these Terms.</li>
                            <li>Upon termination, all rights granted to you will cease, and any unpaid earnings may be
                                forfeited.</li>
                        </ul>

                        <h2>12. Contact Us</h2>
                        <p>For any questions, feedback, or disputes regarding these Terms, please contact us via:</p>
                        <ul>
                            <li>Email: <a href="mailto:easypaytube@gmail.com">easypaytube@gmail.com</a></li>
                            <li>Phone: (+44 7413 710427)</li>
                            <li>WhatsApp and Live Chat available on the website</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 20px 30px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px; padding: 10px 20px; font-weight: 500;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
