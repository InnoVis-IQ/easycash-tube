<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="ThemeStarz">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600">
    <link rel="stylesheet" href="{{asset('web/assets/bootstrap/css/bootstrap.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('web/assets/font-awesome/css/fontawesome-all.min.css')}}">
    <link rel="stylesheet" href="{{asset('web/assets/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('web/assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('web/assets/css/style.css')}}">
	<title>EasyCash Tube</title>

</head>
<body data-spy="scroll" data-target=".navbar">
    <div class="ts-page-wrapper" id="page-top">

        <!--*********************************************************************************************************-->
        <!--************ HERO ***************************************************************************************-->
        <!--*********************************************************************************************************-->
        <header id="ts-hero" class="ts-full-screen ts-separate-bg-element">
            <!--NAVIGATION ******************************************************************************************-->
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top ts-separate-bg-element" data-bg-color="#141a3a">
                <div class="container">
                    <a class="navbar-brand" href="#page-top">
                        Easy Cash Tube<br>
                        <small>Watch. Earn. Repeat</small>
                    </a>
                    <!--end navbar-brand-->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!--end navbar-toggler-->
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav ml-auto text-center">
                            <a class="nav-item nav-link active ts-scroll" href="#page-top">Home <span class="sr-only">(current)</span></a>
                            <a class="nav-item nav-link ts-scroll" href="#overview">Overview</a>
                            <a class="nav-item nav-link ts-scroll" href="#how-it-works">How It Works</a>
                            <a class="nav-item nav-link ts-scroll" href="#about-us">About Us</a>
                             <a class="nav-item nav-link ts-scroll" href="#pricing">Packages</a>
                             <a class="nav-item nav-link ts-scroll mr-2" href="#form-contact">Contact</a>
                            <a class="nav-item nav-link ts-scroll btn btn-primary btn-sm m-1 px-3" href="{{route('login')}}">Login</a>
                            <a class="nav-item nav-link ts-scroll btn btn-info btn-sm m-1 px-3" href="{{route('register')}}">Register</a>

                            {{-- <a class="ts-scroll btn btn-primary btn-sm m-1 px-3 ts-width__auto" href="#">Invest Now</a> --}}
                        </div>
                        <!--end navbar-nav-->
                    </div>
                    <!--end collapse-->
                </div>
                <!--end container-->
            </nav>
            <!--end navbar-->

            <!--HERO CONTENT ****************************************************************************************-->

            <div class="container align-self-center align-items-center">
                <div class="row align-items-center">
                    <div class="col-sm-7 col-md-7  d-sm-block">
                        <h1 data-animate="ts-fadeInUp">Earn Daily Just by Watching Videos!</h1>
                        <div data-animate="ts-fadeInUp" data-delay=".1s">
                            <p class="w-75 text-white mb-5 ts-opacity__50">Join Easycash Tube and get paid every day — earn upto 20% daily on your selected plan by watching just 1 video per day.</p>
                        </div>
                        <a href="{{route('login')}}" class="btn btn-outline-light btn-lg ts-scroll mr-4" data-animate="ts-fadeInUp" data-delay=".2s">
                            Get Started <i class="fa fa-arrow-right small ml-3 ts-opacity__50"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ts-background" data-bg-color="#001135" data-bg-parallax="scroll" data-bg-parallax-speed="3">
                <div class="ts-video-bg ts-opacity__50 ts-parallax-element">
                    <img src="{{asset('web/assets/img/download.jpg')}}" allowfullscreen></img>
                </div>
                <!--end ts-video-->
            </div>
            <!--end ts-background-->

        </header>
        <!--end #hero-->

        <!--*********************************************************************************************************-->
        <!--************ CONTENT ************************************************************************************-->
        <!--*********************************************************************************************************-->
        <main id="ts-content">
            <!--OVERVIEW ********************************************************************************************-->
            <section id="overview" class="">
                <div class="container">
                    <div class="ts-block pr-3 pb-2">
                        <div class="ts-title mb-1">
                            <h2>Overview</h2>
                        </div>
                        <!--end ts-title-->
                        <p>
                            Easycash Tube is a video-based earning platform where users earn upto 20% daily by watching 1 video per day for 5 days. The more you invest, the more you earn
                        </p>
                    </div>
                </div>
                <!--end container-->

            </section>
            <!--END OVERVIEW ****************************************************************************************-->
                
            <!--HOW IT WORKS ****************************************************************************************-->
            <section id="how-it-works" class="ts-block ts-xs-text-center pb-5">
                <div class="container">
                    <div class="ts-title mb-3">
                        <h2>How It Works</h2>
                    </div>
                    <!--end ts-title-->
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="ts-item" data-animate="ts-fadeInUp">
                                <div class="ts-item-content text-center">
                                    <div class="ts-item-header">
                                        <figure class="icon">
                                            <span class="step" data-animate="ts-zoomIn">1</span>
                                            <div class="ts-circle__md" data-bg-color="#ebf1fe">
                                                <img src="{{asset('web/assets/img/a.png')}}" alt="">
                                            </div>
                                        </figure>
                                        <!--end icon-->
                                    </div>
                                    <!--end ts-item-header-->
                                    <div class="ts-item-body">
                                        <h4>Choose Your Plan</h4>
                                        <p>
                                            Start from as low as Rs. 500
                                        </p>
                                    </div>
                                    <!--end ts-item-body-->
                                </div>
                                <!--end ts-item-content-->
                            </div>
                            <!--end ts-item-->
                        </div>
                        <!--end col-xl-4-->
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="ts-item" data-animate="ts-fadeInUp" data-delay="0.1s">
                                <div class="ts-item-content text-center">
                                    <div class="ts-item-header">
                                        <figure class="icon">
                                            <span class="step" data-animate="ts-zoomIn">2</span>
                                            <div class="ts-circle__md" data-bg-color="#ebf1fe">
                                                <img src="{{asset('web/assets/img/b.png')}}" alt="">
                                            </div>
                                        </figure>
                                        <!--end icon-->
                                    </div>
                                    <!--end ts-item-header-->
                                    <div class="ts-item-body">
                                        <h4>Watch Videos</h4>
                                        <p>
                                            Watch 1 video per day for 5 days a week.
                                        </p>
                                    </div>
                                    <!--end ts-item-body-->
                                </div>
                                <!--end ts-item-content-->
                            </div>
                            <!--end ts-item-->
                        </div>
                        <!--end col-xl-4-->
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="ts-item" data-animate="ts-fadeInUp" data-delay="0.2s">
                                <div class="ts-item-content text-center">
                                    <div class="ts-item-header">
                                        <figure class="icon">
                                            <span class="step" data-animate="ts-zoomIn">3</span>
                                            <div class="ts-circle__md" data-bg-color="#ebf1fe">
                                                <img src="{{asset('web/assets/img/c.png')}}" alt="">
                                            </div>
                                        </figure>
                                        <!--end icon-->
                                    </div>
                                    <!--end ts-item-header-->
                                    <div class="ts-item-body">
                                        <h4>Earn Daily</h4>
                                        <p>
                                            Earn 10% of your plan amount daily. Total Return in 5 Days: 50%
                                        </p>
                                    </div>
                                    <!--end ts-item-body-->
                                </div>
                                <!--end ts-item-content-->
                            </div>
                            <!--end ts-item-->
                        </div>
                        <!--end col-xl-4-->
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="ts-item" data-animate="ts-fadeInUp" data-delay="0.2s">
                                <div class="ts-item-content text-center">
                                    <div class="ts-item-header">
                                        <figure class="icon">
                                            <span class="step" data-animate="ts-zoomIn">4</span>
                                            <div class="ts-circle__md" data-bg-color="#ebf1fe">
                                                <img src="{{asset('web/assets/img/c.png')}}" alt="">
                                            </div>
                                        </figure>
                                        <!--end icon-->
                                    </div>
                                    <!--end ts-item-header-->
                                    <div class="ts-item-body">
                                        <h4>Withdraw</h4>
                                        <p>
                                            Withdraw earnings via Easypaisa, JazzCash, or NayaPay
                                        </p>
                                    </div>
                                    <!--end ts-item-body-->
                                </div>
                                <!--end ts-item-content-->
                            </div>
                            <!--end ts-item-->
                        </div>
                        <!--end col-xl-4-->
                    </div>
                    <!--end row-->

                    <ul>
                        <li>✅ Plan is valid for 7 days (5 active earning days + 2 buffer days)</li>
                        <li>✅ Watch. Earn. Repeat.</li>
                    </ul>

                    <br>
                    
                    <h4 class="mb-2">Why Choose Easycash Tube</h4>
                    <ul>
                        <li>✅ Simple and fast income method</li>
                        <li>✅ No technical skills needed</li>
                        <li>✅ Watch anywhere, anytime</li>
                        <li>✅ Reliable payments via Easypaisa, JazzCash, or NayaPay</li>
                        <li>✅ Reinvest anytime after 5 days</li>
                    </ul>

                    <br>
                    
                    <h4 class="mb-2">Start Earning Today!</h4>
                    <p>
                        Whether you're a student, job holder, or freelancer — Easycash Tube gives you a smart way to earn extra income daily by just watching videos.
                        <br><br>
                        🎯 Watch. Earn. Repeat.
                    </p>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('register') }}" class="btn btn-outline-primary mr-2">Join Now</a>
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    </div>
                </div>
                <!--end container-->
            </section>
            <!--END HOW IT WORKS ************************************************************************************-->

            <hr>

            <!--PARTNERS ********************************************************************************************-->
            <!-- <section id="partners" class="ts-block py-4">
                container
                <div class="container">
                    block of logos
                    <div class="d-block d-md-flex justify-content-between align-items-center text-center ts-partners py-3">
                        <a href="#">
                            <img src="{{asset('web/assets/img/logo-01.png')}}" alt="">
                        </a>
                        <a href="#">
                            <img src="{{asset('web/assets/img/logo-02.png')}}" alt="">
                        </a>
                        <a href="#">
                            <img src="{{asset('web/assets/img/logo-03.png')}}" alt="">
                        </a>
                        <a href="#">
                            <img src="{{asset('web/assets/img/logo-04.png')}}" alt="">
                        </a>
                        <a href="#">
                            <img src="{{asset('web/assets/img/logo-05.png')}}" alt="">
                        </a>
                    </div>
                    end logos
                </div>
                end container
            </section> -->
            <!--END PARTNERS ****************************************************************************************-->

            <!--ABOUT US ********************************************************************************************-->
            <section id="about-us">
                <div class="container">
                    <div class="ts-block pr-3">
                        <div class="ts-title mb-3">
                            <h2>About Us</h2>
                        </div>
                        <!--end ts-title-->
                        <p>
                            Easycash Tube is a smart earning platform where you can Watch. Earn. Repeat.
                            <br>
                            We give users in Pakistan the opportunity to earn daily income by watching videos without any hustle. Our goal is to make online earning simple, safe, and accessible for everyone.
                        </p>
                        <h4 class="mb-2">What We Offer:</h4>
                        <ul>
                            <li>✅ Daily returns up to 10% for 5 days on investment plans</li>
                            <li>✅ Exclusive Buy & Sell profit model with up to 20% return after 24 hours</li>
                            <li>✅ Easy withdrawals via Easypaisa, JazzCash, and NayaPay</li>
                            <li>✅ A trusted and growing community of digital earners</li>
                            <li>✅ At Easycash Tube, we believe in transparency, flexibility, and financial freedom for all.</li>
                        </ul>
                    </div>
                </div>
                <!--end container-->

                <div class="ts-background d-none d-sm-block" data-bg-color="#fafafa" data-bg-parallax1="scroll" data-bg-parallax-speed="3">
                    <div class="owl-carousel w-50 ts-push-left__100 h-100 ts-parallax-element1" data-owl-loop="1" data-owl-nav-container="#carousel-external-control">
                        <div class="ts-background-image" data-bg-image="assets/img/about.png')}}"></div>
                        <!-- <div class="ts-background-image" data-bg-image="assets/img/about1.jpg"></div> -->
                    </div>
                    <!--end owl-carousel-->
                </div>
                <!--end ts-background-->

            </section>
            <!--END ABOUT US ****************************************************************************************-->

            <!--NUMBERS *********************************************************************************************-->
            <!-- <section id="numbers" class="ts-block ts-background-is-dark ts-separate-bg-element py-5 ts-xs-text-center" data-bg-image="assets/img/bg-mountain.jpg" data-bg-image-opacity=".1" data-bg-color="#0e1e3d" data-bg-parallax="scroll" data-bg-parallax-speed="3">
                <div class="container">
                    <div class="ts-promo-numbers py-5">
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="ts-promo-number">
                                    <h2 class="ts-opacity__50">140+</h2>
                                    <h5 class="mb-0">Happy Clients</h5>
                                </div>
                                end ts-promo-number
                            </div>
                            end col-sm-3
                            <div class="col-sm-6 col-md-3">
                                <div class="ts-promo-number">
                                    <h2 class="ts-opacity__50">280+</h2>
                                    <h5 class="mb-0">Cases Done</h5>
                                </div>
                                end ts-promo-number
                            </div>
                            end col-sm-3
                            <div class="col-sm-6 col-md-3">
                                <div class="ts-promo-number">
                                    <h2 class="ts-opacity__50">24+</h2>
                                    <h5 class="mb-0">Years Experience</h5>
                                </div>
                                end ts-promo-number
                            </div>
                            end col-sm-3
                            <div class="col-sm-6 col-md-3">
                                <div class="ts-promo-number">
                                    <h2 class="ts-opacity__50">18</h2>
                                    <h5 class="mb-0">Branches Worldwide</h5>
                                </div>
                                end ts-promo-number
                            </div>
                            end col-sm-3
                        </div>
                        end row
                    </div>
                    end ts-promo-numbers
                </div>
                end container
            </section> -->
            <!--END NUMBERS *****************************************************************************************-->




            <!--PRICING *********************************************************************************************-->
            <section id="pricing" class="ts-block" data-bg-color="#f7f7f7">
                <div class="container">
                    <div class="ts-title text-center">
                        <h2>Packages</h2>
                    </div>
                    <!--end ts-title-->
                    <div class="row no-gutters ts-cards-same-height">
                        <!--Price Box-->
                        @foreach($data as $plan)
                        <div class="col-sm-4 col-lg-4 px-2">
                            <div class="card text-center ts-price-box" data-animate="ts-fadeInUp">
                                <div class="card-header p-0">
                                    <h5 class="mb-0 py-3 text-white" data-bg-color="#1b3874">{{$plan['title']}}</h5>
                                    <div class="ts-title py-5 mb-0">
                                        <h3 class="mb-0 font-weight-normal">
                                            {{$plan['amount']}} PKR
                                        </h3>
                                    </div>
                                </div>
                                <!--end card-header-->
                                <div class="card-body p-0">
                                    <ul class="list-unstyled ts-list-divided">
                                        <li>{{$plan['plan_days']}} days plan duration</li>
                                        <li>{{$plan['daily_profit']}} PKR daily profit</li>
                                        <li>{{$plan['total_profit']}} PKR total profit</li>
                                        <li>Withdraw any time</li>
                                    </ul>
                                    <!--end list-->
                                </div>
                                <!--end card-body-->
                                <div class="card-footer bg-transparent pt-0 ts-border-none">
                                    <a href="{{route('login')}}" class="btn btn-outline-dark ts-btn-border-muted">Invest Now</a>
                                </div>
                            </div>
                            <!--end card-->
                        </div>
                        <!--end price-box col-md-4-->
                        @endforeach
                    </div>
                    <!--end row-->
                    <div class="row no-gutters ts-cards-same-height pt-5" style="text-align: center;" data-animate="ts-fadeInUp">
                        Please <a href="{{route('login')}}" class="px-1"> login </a> to see all the investment plans
                    </div>
                </div>
                <!--end container-->
            </section>
            <!--END PRICING *****************************************************************************************-->







        <!--*********************************************************************************************************-->
        <!--************ FOOTER *************************************************************************************-->
        <!--*********************************************************************************************************-->
        <footer id="ts-footer">

            

            <section id="contact" class="ts-separate-bg-element" data-bg-image="assets/img/bg-hand-mobile.jpg" data-bg-image-opacity=".1" data-bg-color="#12264f">
                <div class="container">
                    <div class="ts-box mb-0 p-5 ts-mt__n-10" data-animate="ts-fadeInUp">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>Contact Us</h3>
                                <address>
                                    <figure>
                                        Lahore, Pakistan.
                                    </figure>
                                    <br>
                                    <figure>
                                        <div class="font-weight-bold">Email:</div>
                                        <a href="#">office@easycash-tube.com</a>
                                    </figure>
                                    <figure>
                                        <div class="font-weight-bold">Phone:</div>
                                        +923435560939
                                    </figure>


                                </address>
                                <!--end address-->
                            </div>
                            <!--end col-md-4-->
                            <div class="col-md-8">
                                <h3>Contact Form</h3>
                                <form id="form-contact" method="post" class="clearfix ts-form ts-form-email ts-inputs__transparent" data-php-path="assets/php/email.php">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="form-contact-name">Your Name *</label>
                                                <input type="text" class="form-control" id="form-contact-name" name="name" placeholder="Your Name" required>
                                            </div>
                                            <!--end form-group -->
                                        </div>
                                        <!--end col-md-6 col-sm-6 -->
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="form-contact-email">Your Email *</label>
                                                <input type="email" class="form-control" id="form-contact-email" name="email" placeholder="Your Email" required>
                                            </div>
                                            <!--end form-group -->
                                        </div>
                                        <!--end col-md-6 col-sm-6 -->
                                    </div>
                                    <!--end row -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="form-contact-message">Your Message *</label>
                                                <textarea class="form-control" id="form-contact-message" rows="5" name="message" placeholder="Your Message" required></textarea>
                                            </div>
                                            <!--end form-group -->
                                        </div>
                                        <!--end col-md-12 -->
                                    </div>
                                    <!--end row -->
                                    <div class="form-group clearfix">
                                        <button type="submit" class="btn btn-primary float-right" id="form-contact-submit">Send a Message</button>
                                    </div>
                                    <!--end form-group -->
                                    <div class="form-contact-status"></div>
                                </form>
                                <!--end form-contact -->
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                    <!--end ts-box-->
                    <div class="text-center text-white py-4">
                        <small>© 2023 EasyCash Tube, All Rights Reserved</small>
                    </div>
                </div>
                <!--end container-->
            </section>

        </footer>
        <!--end #footer-->

    </div>
    <!--end page-->

	<script src="{{asset('web/assets/js/custom.hero.js')}}"></script>
	<script src="{{asset('web/assets/js/jquery-3.3.1.min.js')}}"></script>
	<script src="{{asset('web/assets/js/popper.min.js')}}"></script>
	<script src="{{asset('web/assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('web/assets/js/imagesloaded.pkgd.min.js')}}"></script>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyBEDfNcQRmKQEyulDN8nGWjLYPm8s4YB58"></script>
	<script src="{{asset('web/assets/js/isInViewport.jquery.js')}}"></script>
	<script src="{{asset('web/assets/js/jquery.magnific-popup.min.js')}}"></script>
	<script src="{{asset('web/assets/js/owl.carousel.min.js')}}"></script>
	<script src="{{asset('web/assets/js/scrolla.jquery.min.js')}}"></script>
	<script src="{{asset('web/assets/js/jquery.validate.min.js')}}"></script>
	<script src="{{asset('web/assets/js/jquery-validate.bootstrap-tooltip.min.js')}}"></script>
    <script src="{{asset('web/assets/js/custom.js')}}"></script>

</body>
</html>
