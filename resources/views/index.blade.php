@extends('layouts.home')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <style>
        .swiper-container {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            /* Center slide text vertically */
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
    </style>
    <div class="container-lg">
        <h2>หน้าแรก</h2>

        <div class="d-flex justify-content-center">
            <h2 class="font-bold">ยินดีต้อนรับสู่เว็บไซต์</h2> <br>
        </div>
        <div class="d-flex justify-content-center">
            <h2 class="font-bold">PALICO CAT HOTEL</h2>
        </div>

        <br>

        <div class="container" style="position: relative; overflow: hidden;">
            <!-- Slider main container -->
            <div class="swiper-container">
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
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            new Swiper('.swiper-container', {
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                loop: true,
                slidesPerView: 3,
                paginationClickable: true,
                spaceBetween: 20,
            });
        });
    </script>
@endsection
