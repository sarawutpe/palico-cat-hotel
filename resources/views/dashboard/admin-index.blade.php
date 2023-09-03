@extends('layouts.dashboard')
@section('title', 'หน้าแรก')
@section('content')

 <style>

 </style>

    <div class="container-lg">
        <div class="row">
            <!-- item-->
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card mb-4 h-100 text-white bg-primary">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <p>ผู้ใช้</p>
                                <div class="fs-4 fw-semibold">100</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>พนักงาน</p>
                                <div class="fs-4 fw-semibold">100</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>สมาชิก</p>
                                <div class="fs-4 fw-semibold">100</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- item-->
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card mb-4 h-100 text-white bg-info">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <p>ห้องพัก</p>
                                <div class="fs-4 fw-semibold">100</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- item-->
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card mb-4 h-100 text-white bg-warning">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <p>แมว</p>
                                <div class="fs-4 fw-semibold">100</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- item-->
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card mb-4 h-100 text-white bg-danger">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <p>รายได้</p>
                                <div class="fs-4 fw-semibold">100</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row-->
        <div class="card mb-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">ข้อมูลรายได้</h4>
                        <div class="small text-medium-emphasis">January - July 2022</div>
                    </div>
                    <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                        <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">
                            <input class="btn-check" id="option1" type="radio" name="options" autocomplete="off">
                            <label class="btn btn-outline-secondary"> Day</label>
                            <input class="btn-check" id="option2" type="radio" name="options" autocomplete="off"
                                checked="">
                            <label class="btn btn-outline-secondary active"> Month</label>
                            <input class="btn-check" id="option3" type="radio" name="options" autocomplete="off">
                            <label class="btn btn-outline-secondary"> Year</label>
                        </div>
                        <button class="btn btn-primary" type="button">
                            <svg class="icon">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-cloud-download"></use>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
                    <canvas class="chart" id="main-chart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
