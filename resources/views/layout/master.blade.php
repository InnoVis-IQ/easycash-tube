<!DOCTYPE html>
<html>

<head>
    <title>EasyPay Tube</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

    <!-- plugin css -->
    {!! Html::style('assets/plugins/@mdi/font/css/materialdesignicons.min.css') !!}
    {!! Html::style('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') !!}
    <!-- end plugin css -->

    <link href="{{ asset('assets/fa6.4/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/DataTables/datatables.min.css') }}" rel="stylesheet" />

    @stack('plugin-styles')

    <!-- common css -->
    {!! Html::style('css/app.css') !!}
    <!-- end common css -->

    <style>
        .nav-container {
            position: relative;
        }

        .breadcrumb-button {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .nav-item:hover {
            color: white !important;
            background: linear-gradient(270deg, rgb(40, 167, 69) 0%, rgb(0, 123, 255) 100%) !important;
        }
        
        /* Hide sidebar for users */
        @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['user']))
        .sidebar {
            display: none !important;
        }
        
        .main-panel {
            margin-left: 0 !important;
            width: 100% !important;
        }
        
        .page-body-wrapper {
            padding-top: 70px !important;
            padding-bottom: 80px !important;
        }
        @endif

        .fixed-bottom {
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1030 !important;
        }

        .btn-link {
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .btn-link:hover {
            color: #007bff;
        }

        .progress {
            background-color: rgba(255,255,255,0.2);
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .bg-dark {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
        }

        .bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }

        /* Mobile specific styles */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }
            
            .fa-lg {
                font-size: 1.25rem;
            }
            
            .progress {
                height: 6px !important;
            }
            
            .fixed-bottom .btn-link {
                padding: 0.5rem 0.25rem;
            }
            
            .fixed-bottom .small {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .col-6 {
                margin-bottom: 0.5rem;
            }
            
            .d-flex.justify-content-center {
                flex-direction: column;
            }
            
            .d-flex.justify-content-center .btn {
                margin-bottom: 0.5rem;
            }
        }

        /* Mobile specific styles */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }
            
            .fa-lg {
                font-size: 1.25rem;
            }
            
            .progress {
                height: 6px !important;
            }
            
            .fixed-bottom .btn-link {
                padding: 0.5rem 0.25rem;
            }
            
            .fixed-bottom .small {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .col-6 {
                margin-bottom: 0.5rem;
            }
            
            .d-flex.justify-content-center {
                flex-direction: column;
            }
            
            .d-flex.justify-content-center .btn {
                margin-bottom: 0.5rem;
            }
        }
    </style>

    @stack('style')
</head>

<body data-base-url="{{ url('/') }}">
    <div class="container-scroller" id="app">
        @include('layout.header')
        <div class="container-fluid page-body-wrapper">
            @if(Auth::check() && !in_array(strtolower(Auth::user()->role), ['user']))
                @include('layout.sidebar')
            @endif
            <div class="main-panel">
                <div class="content-wrapper">
                    <script>
                        var message = "<?php echo isset($message) ? $message : 0 ?>";
                        if(message != '0'){
                            alert(message);
                        }
                    </script>

                    @yield('content')
                </div>
                @if(Auth::check())
                    @include('layout.footer')
                @endif
            </div>
        </div>
    </div>

    <!-- base js -->
    {!! Html::script('js/app.js') !!}
    {!! Html::script('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') !!}
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <script src="{{ asset('assets/fa6.4/js/all.js') }}"></script>
    <script src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>
    <script>
        $(function() {
            $(".datatable").DataTable();
        });
    </script>
    <!-- common js -->
    {!! Html::script('assets/js/off-canvas.js') !!}
    {!! Html::script('assets/js/hoverable-collapse.js') !!}
    {!! Html::script('assets/js/misc.js') !!}
    {!! Html::script('assets/js/settings.js') !!}
    {!! Html::script('assets/js/todolist.js') !!}
    <!-- end common js -->

    @stack('custom-scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
