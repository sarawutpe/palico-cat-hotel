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
    {{-- <link href="{{ asset('vendors/@coreui/chartjs/css/coreui-chartjs.css') }}" rel="stylesheet"> --}}

    {{-- <script src="{{ asset('vendors/sweetalert2/sweetalert2.css') }}"></script> --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link href="{{ asset('vendors/datepicker/jquery-ui.css') }}" rel="stylesheet">

    {{-- JS --}}
    <script src="{{ asset('vendors/jquery/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('vendors/sweetalert2/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/lodash.min.js') }}"></script>

    <script src="{{ asset('vendors/datepicker/jquery-ui.min.js') }}"></script>
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.9/plugin/buddhistEra.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.9/plugin/utc.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/lodash.debounce@4.0.8/index.min.js"></script> --}}
</head>

<body>
    <script>
        // Init JS
        dayjs.extend(window.dayjs_plugin_utc);

        $.datepicker.regional['th'] = {
          closeText: 'ปิด',
          prevText: 'ก่อน',
          nextText: 'ถัดไป',
          currentText: 'วันนี้',
          monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
          monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
          dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
          dayNamesShort: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
          dayNamesMin: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
          weekHeader: 'Wk',
          dateFormat: 'dd/mm/yy',
          firstDay: 0,
          isRTL: false,
          showMonthAfterYear: false,
          yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['th']);
    </script>

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
        <div class="sidebar-brand d-none d-md-flex gap-2">
            <div class="text-center">
                <img src="{{ asset('assets/img/logo.jpg') }}" class="p-2" width="40" height="40"
                    alt="Logo">
                <span style="font-size: 18px">{{ session('type') }}</span> <br>
            </div>
        </div>
        @include('layouts.dashboard-menu')
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
                            <div class="nav-link py-0" data-coreui-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                <div class="avatar avatar-md">
                                    <img class="avatar-img" src="{{ asset('storage') }}/{{ session('img') }}"
                                        width="40px" height="40px">
                                </div>
                            </div>
                            <div class="dropdown-menu dropdown-menu-end pt-0">
                                <div class="dropdown-header bg-light py-2">
                                    <div class="fw-semibold">Settings</div>
                                </div>
                                <a class="dropdown-item" href="/dashboard/profile">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-user') }}">
                                        </use>
                                    </svg>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="/dashboard/logout">
                                    <svg class="icon me-2">
                                        <use
                                            xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}">
                                        </use>
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
