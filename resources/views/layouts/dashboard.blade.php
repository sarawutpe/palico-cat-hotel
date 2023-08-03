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
        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">

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
                    <a class="nav-link" href="{{ route('dashboard.cat') }}">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                        </svg>แมว
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


            <li class="nav-title">Theme</li>
            <li class="nav-item"><a class="nav-link" href="colors.html">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-drop') }}"></use>
                    </svg> Colors</a></li>
            <li class="nav-item"><a class="nav-link" href="typography.html">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-pencil') }}"></use>
                    </svg> Typography</a></li>
            <li class="nav-title">Components</li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-puzzle') }}"></use>
                    </svg> Base</a>
                <ul class="nav-group-items">
                    <li class="nav-item"><a class="nav-link" href="base/accordion.html"><span
                                class="nav-icon"></span>
                            Accordion</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/breadcrumb.html"><span
                                class="nav-icon"></span>
                            Breadcrumb</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/cards.html"><span class="nav-icon"></span>
                            Cards</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/carousel.html"><span
                                class="nav-icon"></span>
                            Carousel</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="base/collapse.html"><span
                                class="nav-icon"></span>
                            Collapse</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="base/list-group.html"><span
                                class="nav-icon"></span>
                            List
                            group</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/navs-tabs.html"><span
                                class="nav-icon"></span>
                            Navs &amp;
                            Tabs</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/pagination.html"><span
                                class="nav-icon"></span>
                            Pagination</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/placeholders.html"><span
                                class="nav-icon"></span>
                            Placeholders</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/popovers.html"><span
                                class="nav-icon"></span>
                            Popovers</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="base/progress.html"><span
                                class="nav-icon"></span>
                            Progress</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="base/scrollspy.html"><span
                                class="nav-icon"></span>
                            Scrollspy</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/spinners.html"><span
                                class="nav-icon"></span>
                            Spinners</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="base/tables.html"><span class="nav-icon"></span>
                            Tables</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="base/tooltips.html"><span
                                class="nav-icon"></span>
                            Tooltips</a>
                    </li>
                </ul>
            </li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-cursor') }}"></use>
                    </svg> Buttons</a>
                <ul class="nav-group-items">
                    <li class="nav-item"><a class="nav-link" href="buttons/buttons.html"><span
                                class="nav-icon"></span>
                            Buttons</a></li>
                    <li class="nav-item"><a class="nav-link" href="buttons/button-group.html"><span
                                class="nav-icon"></span>
                            Buttons Group</a></li>
                    <li class="nav-item"><a class="nav-link" href="buttons/dropdowns.html"><span
                                class="nav-icon"></span>
                            Dropdowns</a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="charts.html">
                    <svg class="nav-icon">
                        {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use> --}}
                    </svg> Charts</a></li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-notes"></use> --}}
                    </svg> Forms</a>
                <ul class="nav-group-items">
                    <li class="nav-item"><a class="nav-link" href="forms/form-control.html"> Form Control</a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/select.html"> Select</a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/checks-radios.html"> Checks and radios</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="forms/range.html"> Range</a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/input-group.html"> Input group</a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/floating-labels.html"> Floating labels</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="forms/layout.html"> Layout</a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/validation.html"> Validation</a></li>
                </ul>
            </li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-star"></use> --}}
                    </svg> Icons</a>
                <ul class="nav-group-items">
                    <li class="nav-item"><a class="nav-link" href="icons/coreui-icons-free.html"> CoreUI Icons<span
                                class="badge badge-sm bg-success ms-auto">Free</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="icons/coreui-icons-brand.html"> CoreUI Icons -
                            Brand</a></li>
                    <li class="nav-item"><a class="nav-link" href="icons/coreui-icons-flag.html"> CoreUI Icons -
                            Flag</a></li>
                </ul>
            </li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-bell"></use> --}}
                    </svg> Notifications</a>
                <ul class="nav-group-items">
                    <li class="nav-item"><a class="nav-link" href="notifications/alerts.html"><span
                                class="nav-icon"></span>
                            Alerts</a></li>
                    <li class="nav-item"><a class="nav-link" href="notifications/badge.html"><span
                                class="nav-icon"></span>
                            Badge</a></li>
                    <li class="nav-item"><a class="nav-link" href="notifications/modals.html"><span
                                class="nav-icon"></span>
                            Modals</a></li>
                    <li class="nav-item"><a class="nav-link" href="notifications/toasts.html"><span
                                class="nav-icon"></span>
                            Toasts</a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="widgets.html">
                    <svg class="nav-icon">
                        {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-calculator"></use> --}}
                    </svg> Widgets<span class="badge badge-sm bg-info ms-auto">NEW</span></a></li>
            <li class="nav-divider"></li>
            <li class="nav-title">Extras</li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-star"></use> --}}
                    </svg> Pages</a>
                <ul class="nav-group-items">
                    <li class="nav-item"><a class="nav-link" href="login.html" target="_top">
                            <svg class="nav-icon">
                                {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use> --}}
                            </svg> Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.html" target="_top">
                            <svg class="nav-icon">
                                {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use> --}}
                            </svg> Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="404.html" target="_top">
                            <svg class="nav-icon">
                                {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-bug"></use> --}}
                            </svg> Error 404</a></li>
                    <li class="nav-item"><a class="nav-link" href="500.html" target="_top">
                            <svg class="nav-icon">
                                {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-bug"></use> --}}
                            </svg> Error 500</a></li>
                </ul>
            </li>
            <li class="nav-item mt-auto"><a class="nav-link" href="https://coreui.io/docs/templates/installation/"
                    target="_blank">
                    <svg class="nav-icon">
                        {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-description"></use> --}}
                    </svg> Docs</a></li>
            <li class="nav-item"><a class="nav-link nav-link-danger" href="https://coreui.io/pro/" target="_top">
                    <svg class="nav-icon">
                        {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-layers"></use> --}}
                    </svg> Try CoreUI
                    <div class="fw-semibold">PRO</div>
                </a></li>
        </ul>
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>

    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <div style="position: relative; padding-bottom: 4px">
            <header class="header header-sticky">
                <div class="container-fluid" style="flex-wrap: nowrap">
                    <button class="header-toggler px-md-0 me-md-3" type="button"
                        onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                        <svg class="icon icon-lg">
                            {{-- <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-menu"></use> --}}
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
                                    <img class="avatar-img" src="{{ asset('storage')}}/{{session('img')}}" >
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

                {{-- <div class="header-divider"></div>
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb my-0 ms-2">
                        <li class="breadcrumb-item">
                            <span>Home</span>
                        </li>
                        <li class="breadcrumb-item active"><span>Dashboard</span></li>
                    </ol>
                </nav>
            </div> --}}
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
