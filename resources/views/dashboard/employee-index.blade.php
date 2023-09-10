@extends('layouts.dashboard')
@section('title', 'หน้าแรก')
@section('content')

    <style>

    </style>

    <div class="container-lg">
        <div class="row">
            <!-- item-->
            <div class="col-sm-12 col-lg-4 mb-4">
                <div class="card mb-4 h-100 text-white bg-primary">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <p>สมาชิก</p>
                                <div id="user-count" class="fs-4 fw-semibold">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- item-->
            <div class="col-sm-12 col-lg-4 mb-4">
                <div class="card mb-4 h-100 text-white bg-info">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <p>ห้องพัก</p>
                                <div id="room-count" class="fs-4 fw-semibold">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- item-->
            <div class="col-sm-12 col-lg-4 mb-4">
                <div class="card mb-4 h-100 text-white bg-warning">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <p>แมว</p>
                                <div id="cat-count" class="fs-4 fw-semibold">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row-->
        
    </div>

    <script src="vendors/chart.js/js/chart.min.js"></script>
    <script src="vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
    <script src="vendors/@coreui/utils/js/coreui-utils.js"></script>

    <script>
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var stats = null

        $(document).ready(async function() {
            await getAllStats()
        })

        function getAllStats(q = '') {
            utils.setLinearLoading('open')

            const url = q ? `${prefixApi}/api/report/stats?q=${q}` : `${prefixApi}/api/report/stats`
            $.ajax({
                url: url,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    if (!response.success) return
                    const {
                        user_count,
                        room_count,
                        cat_count,
                        income_count,
                        income_stats
                    } = response.data
                    $('#user-count').text(user_count);
                    $('#room-count').text(room_count);
                    $('#cat-count').text(cat_count);
                    $('#income-count').text(`฿${income_count}`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error();
                }
            }).always(function() {
                utils.setLinearLoading('close')
            });
        }
    </script>


@endsection
