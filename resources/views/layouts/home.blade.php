<!DOCTYPE html>
<html lang="en">

<head>
    <base href="">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>Home</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">

    <link rel="manifest" href="{{ asset('assets/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('') }}assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="preload" href="{{ asset('fonts/DBHeavent.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/DBHeavent-Med.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/DBHeavent-Bold.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('vendors/font-awesome/all.min.css') }}">

    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{ asset('vendors/simplebar/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendors/simplebar.css') }}">

    <!-- Main styles for this application-->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="{{ asset('css/examples.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/@coreui/chartjs/css/coreui-chartjs.css') }}" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>
    <div class="w-100">
        <div class="container-lg">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">
                        <img src="{{ asset('assets/img/logo.jpg') }}" alt="" width="50px" height="50px"
                            class="d-inline-block align-top">
                    </a>
                    <button class="navbar-toggler" type="button" data-coreui-toggle="collapse"
                        data-coreui-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav d-flex justify-content-between w-100">
                            <div class="d-flex">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('home') }}">หน้าแรก</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('room') }}">ห้องพักและการจอง</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('service') }}">บริการ</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('price') }}">อัตราค่าบริการ</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('rule') }}">กติกา</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('guide') }}">วิธีการจอง</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('contact') }}">ติดต่อเรา</a>
                                </li>
                            </div>
                            <div class="d-flex">
                                @if (session('is_logged_in') === true)
                                    <li class="nav-item">
                                        <a class="nav-link active" href="dashboard">ระบบจัดการ</a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link active" href="{{ route('register') }}">สมัครสมาชิก</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="{{ route('login') }}">เข้าสู่ระบบ</a>
                                    </li>
                                @endif
                            </div>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        @yield('content')

    </div>

    </div>

    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('vendors/@coreui/coreui/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/simplebar/js/simplebar.min.js') }}"></script>
    <!-- Plugins and scripts required by this view-->
    <script src="{{ asset('vendors/chart.js/js/chart.min.js') }}"></script>
    <script src="{{ asset('vendors/@coreui/chartjs/js/coreui-chartjs.js') }}"></script>
    <script src="{{ asset('vendors/@coreui/utils/js/coreui-utils.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script></script>
</body>

</html>
