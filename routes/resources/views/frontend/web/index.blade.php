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
	<title>MS life Partner</title>

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
                        MS Life Partner
                    </a>
                    <!--end navbar-brand-->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!--end navbar-toggler-->
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav ml-auto text-center">
                            <a class="nav-item nav-link active ts-scroll" href="#page-top">Home <span class="sr-only">(current)</span></a>
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
                        <h1 data-animate="ts-fadeInUp">Invest Now and start Earning Today</h1>
                        <!-- <div data-animate="ts-fadeInUp" data-delay=".1s">
                            <p class="w-75 text-white mb-5 ts-opacity__50">Morbi et nisl a sapien malesuada scelerisque. Suspendisse tempor turpis mattis</p>
                        </div> -->
                        <a href="{{route('login')}}" class="btn btn-outline-light btn-lg ts-scroll mr-4" data-animate="ts-fadeInUp" data-delay=".2s">
                            Invest Now
                            <i class="fa fa-arrow-right small ml-3 ts-opacity__50"></i>
                        </a>
                    </div>
                    <!-- <div class="col-sm-5 offset-lg-1 col-md-4">
                        <form class="ts-form p-4 ts-border-radius__md text-white" data-php-path="assets/php/email.php" data-bg-color="rgba(255,255,255,.2)" data-animate="ts-fadeInUp" data-delay=".2s">
                            <h3>Get a Quote</h3>
                            <p class="text-white d-none d-lg-block">Vivamus fermentum magna non faucibus dignissim. Sed a venenatis </p>

                            <div class="form-group">
                                <input type="text" class="form-control" id="hero-name" name="name" placeholder="Your Name" required>
                            </div>

                            <div class="form-group">
                                <input type="email" class="form-control" id="hero-email" name="email" placeholder="Your Email" required>
                            </div>

                            <div class="form-group">
                                <select class="custom-select form-control" required>
                                    <option selected>Select Interest</option>
                                    <option value="1">Business Grow</option>
                                    <option value="2">Brand Refresh</option>
                                    <option value="3">Crypto Investments</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Get a Quote</button>
                        </form>

                    </div> -->
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

            <!--HOW IT WORKS ****************************************************************************************-->
            <section id="how-it-works" class="ts-block ts-xs-text-center pb-5">
                <div class="container">
                    <div class="ts-title">
                        <h2>How It Works</h2>
                    </div>
                    <!--end ts-title-->
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-xl-4">
                            <div class="ts-item" data-animate="ts-fadeInUp">
                                <div class="ts-item-content">
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
                                        <h4>We're Global</h4>
                                        <p>
                                           MS Life Partner is global company which is working in many countries
                                        </p>
                                    </div>
                                    <!--end ts-item-body-->
                                </div>
                                <!--end ts-item-content-->
                            </div>
                            <!--end ts-item-->
                        </div>
                        <!--end col-xl-4-->
                        <div class="col-sm-6 col-md-4 col-xl-4">
                            <div class="ts-item" data-animate="ts-fadeInUp" data-delay="0.1s">
                                <div class="ts-item-content">
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
                                        <h4>Profitable</h4>
                                        <p>
                                            MS life Partner is profitable where you get your profit on daily basis in 5 days a week.
                                        </p>
                                    </div>
                                    <!--end ts-item-body-->
                                </div>
                                <!--end ts-item-content-->
                            </div>
                            <!--end ts-item-->
                        </div>
                        <!--end col-xl-4-->
                        <div class="col-sm-6 offset-sm-4 col-md-4 offset-md-0 col-xl-4">
                            <div class="ts-item" data-animate="ts-fadeInUp" data-delay="0.2s">
                                <div class="ts-item-content">
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
                                        <h4>We're Secure</h4>
                                        <p>
                                            MS Life Partner is secure company where you can not loose your money
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
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="ts-block pr-3">
                                <div class="ts-title">
                                    <h2>About Us</h2>
                                </div>
                                <!--end ts-title-->
                                <p>
                                    Welcome to our investment firm! Our team is dedicated to helping individuals and organizations achieve their financial goals through strategic investments.

With years of experience in the industry, we have developed a deep understanding of the financial markets and the factors that drive them. We utilize this expertise to create customized investment strategies that align with each client's unique objectives and risk tolerance.
                                </p>
                                <div class="list-group list-group-flush mb-5">
                                    <a href="#collapseOne" class="list-group-item list-group-item-action border-top-0 pl-0 font-weight-bold bg-transparent" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseOne">Our Mission</a>
                                    <div class="collapse" id="collapseOne">
                                        <p class="pt-3 small">
                                            Our mission is to provide our clients with expert investment guidance and superior service to help them achieve their financial goals.
                                        </p>
                                    </div>
                                    <a href="#collapseTwo" class="list-group-item list-group-item-action pl-0 font-weight-bold bg-transparent" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseTwo">We Love Our Clients</a>
                                    <div class="collapse" id="collapseTwo">
                                        <p class="pt-3 small">
                                            We love our clients and are committed to providing them with exceptional service and support throughout their investment journey.
                                        </p>
                                    </div>
                                    <!-- <a href="#collapseThree" class="list-group-item list-group-item-action pl-0 font-weight-bold bg-transparent" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseThree">Awards and Wins</a>
                                    <div class="collapse" id="collapseThree">
                                        <p class="pt-3 small">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                                        </p>
                                    </div> -->
                                </div>
                                <a href="{{route('login')}}" class="btn btn-primary mr-3 my-2">Invest Now</a>
                                <!-- <a href="#" class="btn btn-outline-dark ts-btn-border-muted my-2">Get a Quote</a> -->
                            </div>
                        </div>
                        <!--end col-md-6-->
                    </div>
                    <!--end row-->
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
                        <div class="col-sm-4 col-lg-4">
                            <div class="card text-center ts-price-box" data-animate="ts-fadeInUp">
                                <div class="card-header p-0">
                                    <h5 class="mb-0 py-3 text-white" data-bg-color="#1b3874">{{$data[0]['title']}}</h5>
                                    <div class="ts-title py-5 mb-0">
                                        <h3 class="mb-0 font-weight-normal">
                                            {{$data[0]['amount']}} PKR
                                        </h3>
                                    </div>
                                </div>
                                <!--end card-header-->
                                <div class="card-body p-0">
                                    <ul class="list-unstyled ts-list-divided">
                                        <li>{{$data[0]['plan_days']}} days plan duration</li>
                                        <li>{{$data[0]['daily_profit']}} PKR daily profit</li>
                                        <li>{{$data[0]['total_profit']}} PKR total profit</li>
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

                        <!--Price Box Promoted-->
                        <div class="col-sm-4 col-lg-4">
                            <div class="card text-center ts-price-box ts-price-box__promoted" data-animate="ts-fadeInUp" data-delay="0.1s">
                                <div class="card-header p-0" data-bg-color="#346de0">
                                    <h5 class="mb-0 py-3 text-white" data-bg-color="#3a79f9">{{$data[3]['title']}}</h5>
                                    <div class="ts-title text-white py-5 mb-0">
                                        <h3 class="mb-0 font-weight-normal">
                                            {{$data[3]['amount']}} PKR
                                        </h3>
                                    </div>
                                </div>
                                <!--end card-header-->
                                <div class="card-body p-0">
                                    <ul class="list-unstyled ts-list-divided">
                                        <li>{{$data[3]['plan_days']}} days plan duration</li>
                                        <li>{{$data[3]['daily_profit']}} PKR daily profit</li>
                                        <li>{{$data[3]['total_profit']}} PKR total profit</li>
                                        <li>Withdraw any time</li>
                                    </ul>
                                    <!--end list-->
                                </div>
                                <!--end card-body-->
                                <div class="card-footer bg-transparent pt-0 ts-border-none">
                                    <a href="{{route('login')}}" class="btn btn-primary">Invest Now</a>
                                </div>
                            </div>
                            <!--end card-->
                        </div>
                        <!--end price-box col-md-4-->

                        <!--Price Box-->
                        <div class="col-sm-4 col-lg-4">
                            <div class="card text-center ts-price-box" data-animate="ts-fadeInUp" data-delay="0.2s">
                                <div class="card-header p-0">
                                    <h5 class="mb-0 py-3 text-white" data-bg-color="#1b3874">{{$data[7]['title']}}</h5>
                                    <div class="ts-title py-5 mb-0">
                                        <h3 class="mb-0 font-weight-normal">
                                            {{$data[7]['amount']}} PKR
                                        </h3>
                                    </div>
                                </div>
                                <!--end card-header-->
                                <div class="card-body p-0">
                                    <ul class="list-unstyled ts-list-divided">
                                        <li>{{$data[7]['plan_days']}} days plan duration</li>
                                        <li>{{$data[7]['daily_profit']}} PKR daily profit</li>
                                        <li>{{$data[7]['total_profit']}} PKR total profit</li>
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
                                        <a href="#">office@mslifepartner.com</a>
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
                        <small>© 2023 MS life Partner, All Rights Reserved</small>
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
