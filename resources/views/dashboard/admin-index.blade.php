@extends('layouts.dashboard')
@section('title', 'หน้าแรก')
@section('content')

    <div class="container-lg">
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-primary">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">26K <span class="fs-6 fw-normal">(-12.4%
                                    <svg class="icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-arrow-bottom">
                                        </use>
                                    </svg>)</span></div>
                            <div>ผู้ใช้</div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <svg class="icon">
                                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-options"></use>
                                </svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item"
                                    href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a
                                    class="dropdown-item" href="#">Something else
                                    here</a></div>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas class="chart" id="card-chart1" height="70"></canvas>
                    </div>
                </div>
            </div>
            <!-- /.col-->
            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-info">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">$6.200 <span class="fs-6 fw-normal">(40.9%
                                    <svg class="icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-arrow-top">
                                        </use>
                                    </svg>)</span></div>
                            <div>พนักงาน</div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <svg class="icon">
                                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-options"></use>
                                </svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item"
                                    href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a
                                    class="dropdown-item" href="#">Something else
                                    here</a></div>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas class="chart" id="card-chart2" height="70"></canvas>
                    </div>
                </div>
            </div>
            <!-- /.col-->
            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-warning">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">2.49% <span class="fs-6 fw-normal">(84.7%
                                    <svg class="icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-arrow-top">
                                        </use>
                                    </svg>)</span></div>
                            <div>แมว</div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <svg class="icon">
                                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-options"></use>
                                </svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item"
                                    href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a
                                    class="dropdown-item" href="#">Something else
                                    here</a></div>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3" style="height:70px;">
                        <canvas class="chart" id="card-chart3" height="70"></canvas>
                    </div>
                </div>
            </div>
            <!-- /.col-->
            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-danger">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">44K <span class="fs-6 fw-normal">(-23.6%
                                    <svg class="icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-arrow-bottom">
                                        </use>
                                    </svg>)</span></div>
                            <div>รายได้</div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <svg class="icon">
                                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-options"></use>
                                </svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item"
                                    href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a
                                    class="dropdown-item" href="#">Something else
                                    here</a></div>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas class="chart" id="card-chart4" height="70"></canvas>
                    </div>
                </div>
            </div>
            <!-- /.col-->
        </div>
        <!-- /.row-->
        <div class="card mb-4">
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
