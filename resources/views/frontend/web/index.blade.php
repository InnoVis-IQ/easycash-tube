<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="EasyPay Tube">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <title>EasyPay Tube - Watch. Earn. Repeat.</title>
    
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #007bff;
            --dark-color: #212529;
            --light-color: #f8f9fa;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .section-padding {
            padding: 80px 0;
        }
        
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        
        .plan-card {
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .plan-card:hover {
            border-color: var(--primary-color);
        }
        
        .plan-header {
            background: var(--primary-color);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }
        
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary-custom:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: white;
        }
        
        .btn-outline-secondary-custom {
            color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-secondary-custom:hover {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .faq-item {
            border: 1px solid #dee2e6;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        
        .faq-header {
            background: #f8f9fa;
            padding: 15px 20px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        
        .faq-header:hover {
            background: #e9ecef;
        }
        
        .faq-body {
            padding: 20px;
            border-top: 1px solid #dee2e6;
        }
        
        .footer {
            background: var(--dark-color);
            color: white;
            padding: 40px 0 20px;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            margin: 0 5px;
            transition: transform 0.3s ease;
        }
        
        .social-icon:hover {
            transform: scale(1.1);
            color: white;
        }
        
        .check-list {
            list-style: none;
            padding: 0;
        }
        
        .check-list li {
            padding: 8px 0;
            position: relative;
            padding-left: 30px;
        }
        
        .check-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--primary-color);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <i class="fas fa-play-circle me-2"></i>
                EasyPay Tube
                <small class="d-block" style="font-size: 0.7rem; color: #6c757d;">Watch. Earn. Repeat.</small>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#plans">Plans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('register') }}" class="btn btn-primary-custom btn-sm">Get Started</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary-custom btn-sm">Login to Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center hero-content">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Earn Daily Just by Watching Videos!</h1>
                    <p class="lead mb-4">Join EasyPay Tube and get paid every day — earn up to 20% daily on your selected plan by watching just 1 video per day.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4">Join Now</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">Login</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-play-circle" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">How It Works</h2>
                    <p class="lead text-muted">Simple steps to start earning today</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h5 class="fw-bold mb-3">1. Choose Your Plan</h5>
                            <p class="text-muted">Starting from Rs. 500</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-play"></i>
                            </div>
                            <h5 class="fw-bold mb-3">2. Watch 1 Video Daily</h5>
                            <p class="text-muted">Takes a few minutes</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-coins"></i>
                            </div>
                            <h5 class="fw-bold mb-3">3. Earn Instantly</h5>
                            <p class="text-muted">Up to 20% return added daily</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <h5 class="fw-bold mb-3">4. Withdraw Easily</h5>
                            <p class="text-muted">Easypaisa, JazzCash, or NayaPay</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Investment Plans Section -->
    <section id="plans" class="section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">Investment Plans</h2>
                    <p class="lead text-muted">Choose the plan that fits your investment goals</p>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($plans as $plan)
                <div class="col-md-6 col-lg-4">
                    <div class="card plan-card h-100 shadow-sm">
                        <div class="plan-header text-center">
                            <h4 class="mb-0">{{ $plan->title }}</h4>
                        </div>
                        <div class="card-body text-center p-4">
                            <h3 class="text-primary fw-bold mb-3">Rs. {{ number_format($plan->amount) }}</h3>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><strong>Daily:</strong> Rs. {{ number_format($plan->daily_profit) }}</li>
                                <li class="mb-2"><strong>Total:</strong> Rs. {{ number_format($plan->total_profit) }}</li>
                                <li class="mb-2"><strong>Duration:</strong> {{ $plan->plan_days }} Days</li>
                            </ul>
                            <small class="text-muted d-block mb-3">Only once per user</small>
                            <a href="{{ route('login') }}" class="btn btn-primary-custom w-100">Invest Now</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-end">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary-custom">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Why Choose Us</h2>
                    <ul class="check-list">
                        <li>Simple and fast income method</li>
                        <li>No technical skills needed</li>
                        <li>Watch anywhere, anytime</li>
                        <li>Reliable payments via Easypaisa, JazzCash, or NayaPay</li>
                        <li>Reinvest anytime after 5 days</li>
                    </ul>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-star" style="font-size: 8rem; color: var(--primary-color); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="section-padding text-white" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">Our Growing Community</h2>
                    <p class="lead">Join thousands of satisfied users who trust EasyPay Tube</p>
                </div>
            </div>
            
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold mb-2">15,847</h3>
                        <p class="mb-0">Total Users</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold mb-2">Rs 2.4M</h3>
                        <p class="mb-0">Total Paid Out</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold mb-2">98.5%</h3>
                        <p class="mb-0">Success Rate</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold mb-2">4.9★</h3>
                        <p class="mb-0">User Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">Frequently Asked Questions</h2>
                    <p class="lead text-muted">Everything you need to know about EasyPay Tube</p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="faq-item">
                            <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <h5 class="mb-0 fw-bold">What is EasyPay Tube?</h5>
                            </div>
                            <div id="faq1" class="collapse faq-body" data-bs-parent="#faqAccordion">
                                <p>EasyPay Tube is a video-based earning platform where users can earn daily income by watching videos. It's a simple and legitimate way to make money online.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <h5 class="mb-0 fw-bold">How do I earn from it?</h5>
                            </div>
                            <div id="faq2" class="collapse faq-body" data-bs-parent="#faqAccordion">
                                <p>Choose an investment plan, watch 1 video daily for 5 days, and earn up to 20% daily returns on your investment. The process is simple and takes only a few minutes each day.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <h5 class="mb-0 fw-bold">What plans are available?</h5>
                            </div>
                            <div id="faq3" class="collapse faq-body" data-bs-parent="#faqAccordion">
                                <p>We offer 5 different plans: Starter (Rs. 500), Bronze (Rs. 2,000), Silver (Rs. 6,000), Gold (Rs. 12,000), and Diamond (Rs. 25,000). Each plan can only be used once per user.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq4">
                                <h5 class="mb-0 fw-bold">How long does a plan last?</h5>
                            </div>
                            <div id="faq4" class="collapse faq-body" data-bs-parent="#faqAccordion">
                                <p>Each plan lasts for 5 days of active earning. You need to watch 1 video daily for 5 consecutive days to complete the plan and receive your total returns.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq5">
                                <h5 class="mb-0 fw-bold">What payment methods are supported?</h5>
                            </div>
                            <div id="faq5" class="collapse faq-body" data-bs-parent="#faqAccordion">
                                <p>We support withdrawals through Easypaisa, JazzCash, and NayaPay. These are the most popular and secure payment methods in Pakistan.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-header" data-bs-toggle="collapse" data-bs-target="#faq6">
                                <h5 class="mb-0 fw-bold">Is it legit?</h5>
                            </div>
                            <div id="faq6" class="collapse faq-body" data-bs-parent="#faqAccordion">
                                <p>Yes, EasyPay Tube is a legitimate platform. We have thousands of satisfied users who earn daily. Our transparent business model and secure payment system ensure a safe earning experience.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="section-padding bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">About Us</h2>
                    <p class="lead mb-4">EasyPay Tube is a smart earning platform where you Watch. Earn. Repeat. We offer users in Pakistan a simple, secure way to earn daily income online just by watching videos.</p>
                    <p>Our mission is to provide a legitimate and accessible way for people to earn extra income without requiring any special skills or technical knowledge. With our transparent business model and reliable payment system, we've helped thousands of users achieve their financial goals.</p>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-users" style="font-size: 8rem; color: var(--secondary-color); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">EasyPay Tube</h5>
                    <p class="mb-3">Watch. Earn. Repeat.</p>
                    <p class="small">Your trusted partner for online earning in Pakistan.</p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none">Terms & Conditions</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#contact" class="text-decoration-none">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3">Contact Info</h6>
                    <p class="small text-white mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        easypaytube@gmail.com
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="small text-white mb-0">© 2025 EasyPay Tube. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scrolling -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
