<!DOCTYPE html>
<html>

<head>
    <!-- Site made with Mobirise Online Website Builder v5.0.0, https://a.mobirise.com -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="generator" content="Mobirise v5.0.0, a.mobirise.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
    <meta name="description" content="">
    <title>Home</title>
    <link rel="stylesheet"
        href="https://r.mobirisesite.com/296271/assets/web/assets/mobirise-icons2/mobirise2.css?rnd=1681045951221">
    <link rel="stylesheet"
        href="https://r.mobirisesite.com/296271/assets/bootstrap/css/bootstrap.min.css?rnd=1681045951222">
    <link rel="stylesheet"
        href="https://r.mobirisesite.com/296271/assets/bootstrap/css/bootstrap-grid.min.css?rnd=1681045951222">
    <link rel="stylesheet"
        href="https://r.mobirisesite.com/296271/assets/bootstrap/css/bootstrap-reboot.min.css?rnd=1681045951222">
    <link rel="stylesheet" href="https://r.mobirisesite.com/296271/assets/dropdown/css/style.css?rnd=1681045951222">
    <link rel="stylesheet" href="https://r.mobirisesite.com/296271/assets/socicon/css/styles.css?rnd=1681045951222">
    <link rel="stylesheet" href="https://r.mobirisesite.com/296271/assets/theme/css/style.css?rnd=1681045951222">
    <link rel="stylesheet" href="https://r.mobirisesite.com/296271/assets/css/mbr-additional.css?rnd=1681045951222"
        type="text/css">




</head>

<body>

    <section data-bs-version="5" class="menu menu2 cid-sFCw1qGFAI" once="menu" id="menu2-23">

        <nav class="navbar navbar-dropdown navbar-fixed-top navbar-expand-lg">
            <div class="container-fluid">
                <div class="navbar-brand">

                    <span class="navbar-caption-wrap"><a class="navbar-caption text-black display-7"
                            href="{{route('home')}}">MSLifePartner</a></span>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse"
                    data-target="#navbarSupportedContent" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav nav-dropdown" data-app-modern-menu="true">
                        <li class="nav-item"><a class="nav-link link text-black text-primary display-4"
                                href="{{route('home')}}">Home</a></li>
                        <li class="nav-item"><a class="nav-link link text-black text-primary display-4"
                                href="index.html#header14-1j">About</a></li>
                        <li class="nav-item"><a class="nav-link link text-black text-primary display-4"
                                href="index.html#content4-1q">Plans</a>
                        </li>
                        <li class="nav-item"><a class="nav-link link text-black text-primary display-4"
                                href="index.html#contacts2-1r">Contacts</a></li>
                    </ul>

                    <div class="navbar-buttons mbr-section-btn"><a class="btn btn-primary display-4" href="{{route('login')}}">Login</a></div>
                    <div class="navbar-buttons mbr-section-btn"><a class="btn btn-primary display-4" href="{{route('register')}}">Register</a></div>
                </div>
            </div>
        </nav>
    </section>

    <section data-bs-version="5" class="content4 cid-sFzIdnt1ra" id="content4-1q">


        <div class="container">
            <div class="row justify-content-center">
                <div class="title col-md-12 col-lg-10">
                    <h3 class="mbr-section-title mbr-fonts-style align-center mb-4 display-2"><strong>Our Investment Plan</strong></h3>
                </div>
            </div>
        </div>
    </section>

    <section data-bs-version="5" class="pricing2 cid-sFzDdjoEIw" id="pricing2-1k">



        <div class="container">
            <div class="row justify-content-center">
                @foreach ($data as $item)
                    <div class="col-12 col-md-6 align-center col-lg-3 my-3">
                        <div class="plan">
                            <div class="plan-header">
                                <h6 class="plan-title mbr-fonts-style mb-3 display-5">
                                    <strong>{{$item['title']}}</strong>
                                </h6>
                                <div class="plan-price">
                                    <p class="price mbr-fonts-style m-0 display-2"><strong>Rs. {{$item['daily_profit']}}</strong></p>
                                    <p class="price-term mbr-fonts-style mb-3 display-7"><strong>Per day</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="plan-body">
                                <div class="plan-list mb-4">
                                    <ul class="list-group mbr-fonts-style list-group-flush display-7">
                                        <li class="list-group-item">Investment Amount: {{$item['amount']}}</li>
                                        <li class="list-group-item">Plan Duration: {{$item['plan_days']}}</li>
                                        <li class="list-group-item">Total Return: {{$item['total_profit']}}</li>
                                    </ul>
                                </div>
                                <div class="mbr-section-btn text-center"><a href="{{route('login')}}" class="btn btn-primary-outline display-4">Invest</a></div>
                            </div>
                        </div>
                    </div>
                @endforeach



            </div>
        </div>
    </section>

    <section data-bs-version="5" class="features23 cid-sFAyHxWQ1N" id="features24-20">



        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card-wrapper mb-4">
                        <div class="card-box align-center">
                            <h4 class="card-title mbr-fonts-style mb-4 display-2">
                                <strong>Steps to Start Traiding</strong>
                            </h4>
                            {{-- <p class="mbr-text mbr-fonts-style mb-4 display-7">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Nunc ut dapibus massa, efficitur varius augue. In vel elit lorem. Fusce
                                et dui fringilla, suscipit nulla sed, viverra nunc. Nulla ut justo ut enim vehicula
                                maximus. Nam et neque tempus, ultricies purus vel, suscipit leo.</p> --}}

                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="item first mbr-flex p-4">
                        <div class="icon-wrap w-100">
                            <div class="icon-box">
                                <span class="step-number mbr-fonts-style display-5">1</span>
                            </div>
                        </div>

                        <div class="text-box">
                            <h4 class="icon-title card-title mbr-black mbr-fonts-style display-7"><strong>Deposit Money</strong></h4>
                            <h5 class="mbr-text mbr-black mbr-fonts-style display-4">Deposit money in your account and once the transaction is approved by the admin you can subscribe to any investment plan.</h5>
                        </div>
                    </div>
                    <!-- <span mbr-icon class="mbr-iconfont mobi-mbri-devices mobi-mbri"></span> -->
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="item mbr-flex p-4">
                        <div class="icon-wrap w-100">
                            <div class="icon-box">
                                <span class="step-number mbr-fonts-style display-5">2</span>
                            </div>
                        </div>
                        <div class="text-box">
                            <h4 class="icon-title card-title mbr-black mbr-fonts-style display-7">
                                <strong>Buy Investment Plan</strong>
                            </h4>
                            <h5 class="mbr-text mbr-black mbr-fonts-style display-4">Buy an investment plan according to your choice</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="item mbr-flex p-4">
                        <div class="icon-wrap w-100">
                            <div class="icon-box">
                                <span class="step-number mbr-fonts-style display-5">3</span>
                            </div>
                        </div>
                        <div class="text-box">
                            <h4 class="icon-title card-title mbr-black mbr-fonts-style display-7"><strong>Withdraw Money</strong></h4>
                            <h5 class="mbr-text mbr-black mbr-fonts-style display-4">Once the subscribed plan duration is over, you can withdraw money to your account.</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section data-bs-version="5" class="footer3 cid-sFCygHrmNf" once="footers" id="footer3-24">
        <div class="container-fluid">
            <div class="row align-center mbr-white">
                <div class="row row-links">
                    <ul class="foot-menu">
                        <li class="foot-menu-item mbr-fonts-style display-7"><a href="{{route('home')}}" class="text-white">Home</a></li>
                        <li class="foot-menu-item mbr-fonts-style display-7">About</li>
                        <li class="foot-menu-item mbr-fonts-style display-7">Plan</li>
                        <li class="foot-menu-item mbr-fonts-style display-7">Contacts</li>
                    </ul>
                </div>

                <div class="row row-copirayt">
                    <p class="mbr-text mb-0 mbr-fonts-style mbr-white align-center display-7">
                        © Copyright 2023 MSLifePartner.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <script src="assets/web/assets/jquery/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/smoothscroll/smooth-scroll.js"></script>
    <script src="assets/ytplayer/index.js"></script>
    <script src="assets/dropdown/js/navbar-dropdown.js"></script>
    <script src="assets/theme/js/script.js"></script>
    <script src="assets/formoid/formoid.min.js"></script>



</body>

</html>
