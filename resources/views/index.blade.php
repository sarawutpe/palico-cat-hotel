@extends('layouts.home')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <style>
        .swiper {
            width: 100%;
            height: 100%;
            max-width: 1200px;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }

        .swiper-button-prev:active,
        .swiper-button-next:active {
            border-radius: 8px;
        }

        .swiper-button-prev::after,
        .swiper-button-next:after {
            font-size: 32px !important;
        }

        .swiper-button-prev,
        .swiper-button-next {
            transition-duration: .4s;
            width: 48px !important;
            height: 48px !important;
            border-radius: 18px;
        }

        .swiper-button-prev {
            margin-right: -1.25rem;
        }

        .swiper-button-next {
            margin-left: -1.25rem;
        }

        .swiper-button-prev,
        .swiper-button-next {
            background-color: rgba(215, 227, 255, 0.4);
        }

        .swiper-button-prev:hover,
        .swiper-button-next:hover {
            background-color: #d7e3ff;
        }

        .swiper-pagination-bullet.swiper-pagination-bullet-active {
            background-color: #fff;
        }
    </style>


    <div class="container-lg pb-4">
        <h2>หน้าแรก</h2>

        <div class="d-flex justify-content-center">
            <h2 class="font-bold">ยินดีต้อนรับสู่เว็บไซต์</h2> <br>
        </div>
        <div class="d-flex justify-content-center">
            <h2 class="font-bold">PALICO CAT HOTEL</h2>
        </div>
        <br>

        <div class="container mb-4" style="position: relative; overflow: hidden;">
            <!-- Slider main container -->
            <div class="swiper">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (1).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (2).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (3).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (4).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (5).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (6).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (7).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (8).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (9).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (10).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (11).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (12).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/img/slide (13).jpg') }}" width="100%" height="100%" alt="">
                    </div>
                </div>
            </div>
            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>
            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
        <br>
        <div class="container mb-4">
            <div class="d-flex justify-content-start">
                <h2 class="font-bold">อัตราค่าบริการ</h2>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12"><img src="{{ asset('assets/img/standard-room.jpg') }}" width="100%" alt=""></div>
                <div class="col-md-4 col-sm-12"><img src="{{ asset('assets/img/superior-room.jpg') }}" width="100%" alt=""></div>
                <div class="col-md-4 col-sm-12"><img src="{{ asset('assets/img/deluxe-room.jpg') }}" width="100%" alt=""></div>
            </div>
        </div>
        <br>
        <div class="container mb-4">
            <div class="d-flex justify-content-start">
                <h2 class="font-bold">บริการ</h2>
            </div>
            <div class="row">
                <img src="{{ asset('assets/img/service.jpg') }}" width="500px" alt="">
            </div>
        </div>
        <br>
        <div class="container mb-4">
            <div class="d-flex justify-content-start">
                <h2 class="font-bold">เงื่อนไขการจอง</h2>
            </div>
            <div class="row">
                <img src="{{ asset('assets/img/rule.jpg') }}" width="500px" alt="">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            new Swiper('.swiper', {
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                },
                loop: true,
                slidesPerView: 4,
                paginationClickable: true,
                spaceBetween: 20,
            });
        });
    </script>
@endsection
