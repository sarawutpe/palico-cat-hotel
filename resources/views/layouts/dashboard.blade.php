<!DOCTYPE html>
<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>@yield('title', '')</title>
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
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

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
    {{-- <link href="{{ asset('vendors/@coreui/chartjs/css/coreui-chartjs.css') }}" rel="stylesheet"> --}}

    {{-- <script src="{{ asset('vendors/sweetalert2/sweetalert2.css') }}"></script> --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


    {{-- JS --}}
    <script src="{{ asset('vendors/jquery/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('vendors/sweetalert2/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/lodash.min.js') }}"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/lodash.debounce@4.0.8/index.min.js"></script> --}}

</head>

<body>
    <style>
        .swal2-confirm.swal2-styled,
        .swal2-cancel.swal2-styled {
            background: transparent;
            border: 1px solid #ddd;
            font-weight: 600;
            font-size: 1.4rem;
            padding: 4px 16px;
        }

        .swal2-confirm.swal2-styled:focus,
        .swal2-cancel.swal2-styled:focus {
            box-shadow: 0 0 0 2px rgba(0, 0, 21, 0.125);
        }

        .swal2-confirm.swal2-styled {
            color: #2472cb;
        }

        .swal2-cancel.swal2-styled {
            color: #d4574a;
        }
    </style>

    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
        <div class="sidebar-brand d-none d-md-flex">
            <svg class="sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
                <use xlink:href="{{ asset('assets/brand/coreui.svg#full') }}"></use>
            </svg>
            <svg class="sidebar-brand-narrow" width="46" height="46" alt="CoreUI Logo">
                <use xlink:href="{{ asset('assets/brand/coreui.svg#signet') }}"></use>
            </svg>
            <div>
                <span style="font-size: 18px">{{ session('type') }}</span> <br>
            </div>
        </div>
        <ul class="sidebar-nav" data-coreui="navigationaa" data-simplebar="">

            @if (session('type') === 'MEMBER')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                        </svg>หน้าแรก
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.cat') }}">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                        </svg>แมว
                    </a>
                </li>
            @endif

            @if (session('type') === 'EMPLOYEE')
            @endif

            @if (session('type') === 'ADMIN')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                        </svg>หน้าแรก
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.employee') }}">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                        </svg>พนักงาน
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.room') }}">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                        </svg>ห้องพัก
                    </a>
                </li>
            @endif


        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>

    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <div style="position: relative; padding-bottom: 4px">
            <header class="header header-sticky">
                <div class="container-fluid" style="flex-wrap: nowrap">
                    <button class="header-toggler px-md-0 me-md-3" type="button"
                        onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                        <svg class="icon icon-lg">
                            <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
                        </svg>
                    </button><a class="header-brand d-md-none" href="#">
                        <svg width="118" height="46" alt="CoreUI Logo">
                            <use xlink:href="{{ asset('assets/brand/coreui.svg#full') }}"></use>
                        </svg></a>

                    <ul class="header-nav d-none d-md-flex justify-content-between w-100">
                        <h4>
                            @yield('title', '')
                        </h4>
                        <div class="">
                            <input type="text" name="search_input" class="form-control" placeholder="ค้นหา"
                                style="border-radius: 50px;">
                        </div>
                    </ul>

                    <ul class="header-nav ms-3">
                        <li class="nav-item dropdown">
                            <div class="nav-link py-0" data-coreui-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <div class="avatar avatar-md">
                                    <img class="avatar-img" src="{{ asset('storage')}}/{{session('img')}}" width="40px" height="40px">
                                </div>
                            </div>
                            <div class="dropdown-menu dropdown-menu-end pt-0">
                                <div class="dropdown-header bg-light py-2">
                                    <div class="fw-semibold">Settings</div>
                                </div>
                                <a class="dropdown-item" href="/dashboard/profile">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                                    </svg>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="/dashboard/logout">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}"></use>
                                    </svg>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </header>

            {{-- Linear indeterminate --}}
            <div id="linear-indeterminate" class="linear-progress-content">
                <div class="linear-progress"></div>
            </div>
        </div>
        <div class="body flex-grow-1 px-3">
            <br>
            @yield('content')
        </div>
        <footer class="footer">
            <div>© 2023 PALICO CAT HOTEL.</div>
        </footer>
    </div>

    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('vendors/@coreui/coreui/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/simplebar/js/simplebar.min.js') }}"></script>
    <!-- Plugins and scripts required by this view-->
    <script src="{{ asset('vendors/chart.js/js/chart.min.js') }}"></script>
    <script src="{{ asset('vendors/@coreui/chartjs/js/coreui-chartjs.js') }}"></script>
    <script src="{{ asset('vendors/@coreui/utils/js/coreui-utils.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
