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
                                <p>สมาชิก</p>
                                <div id="user-count" class="fs-4 fw-semibold">0</div>
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
                                <div id="room-count" class="fs-4 fw-semibold">0</div>
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
                                <div id="cat-count" class="fs-4 fw-semibold">0</div>
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
                                <div id="income-count" class="fs-4 fw-semibold">0</div>
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
                        <div id="label-stats-range" class="small text-medium-emphasis"></div>
                    </div>
                    <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                        <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">
                            <input class="btn-check" id="option-d" type="radio" name="option-stats" autocomplete="off">
                            <label class="btn btn-outline-secondary" onclick="handleFilterStats('d')">วัน</label>
                            <input class="btn-check" id="option-m" type="radio" name="option-stats" autocomplete="off">
                            <label class="btn btn-outline-secondary" onclick="handleFilterStats('m')">เดือน</label>
                            <input class="btn-check" id="option-y" type="radio" name="option-stats" autocomplete="off">
                            <label class="btn btn-outline-secondary" onclick="handleFilterStats('y')">ปี</label>
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

    <script src="vendors/chart.js/js/chart.min.js"></script>
    <script src="vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
    <script src="vendors/@coreui/utils/js/coreui-utils.js"></script>

    <script>
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var stats = null
        var mainChart = null

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

                    if (mainChart) {
                        mainChart.destroy();
                    }
                    drawChart(response.data)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error();
                }
            }).always(function() {
                utils.setLinearLoading('close')
            });
        }

        function drawChart(data) {
            if (!data.income_stats || !Array.isArray(data.income_stats)) return

            const labelStatsRangeHtml = $('#label-stats-range')
            const mainChartHtml = $('#main-chart')

            // Set label stats range
            if (data.income_stats.length === 1) {
                labelStatsRangeHtml.text(formatDate(data.income_stats[0].day));
            } else {
                const lastIndex = data.income_stats.length - 1
                labelStatsRangeHtml.text(
                    `${formatDate(data.income_stats[0].day)} - ${formatDate(data.income_stats[lastIndex].day)}`);
            }

            const labelList = []
            const dataList = []
            data.income_stats.forEach(item => {
                labelList.push(formatDate(item.day))
                dataList.push(parseInt(item.income_count))
            });

            Chart.defaults.pointHitDetectionRadius = 1;
            Chart.defaults.plugins.tooltip.enabled = false;
            Chart.defaults.plugins.tooltip.mode = 'index';
            Chart.defaults.plugins.tooltip.position = 'nearest';
            Chart.defaults.plugins.tooltip.external = coreui.ChartJS.customTooltips;
            Chart.defaults.defaultFontColor = '#646470';

            mainChart = new Chart(mainChartHtml, {
                type: 'line',
                data: {
                    labels: labelList,
                    datasets: [{
                        label: 'สถิติ',
                        backgroundColor: coreui.Utils.hexToRgba(coreui.Utils.getStyle('--cui-info'), 10),
                        borderColor: coreui.Utils.getStyle('--cui-info'),
                        pointHoverBackgroundColor: '#fff',
                        borderWidth: 2,
                        data: dataList,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    elements: {
                        line: {
                            tension: 0.4
                        },
                        point: {
                            radius: 0,
                            hitRadius: 10,
                            hoverRadius: 4,
                            hoverBorderWidth: 3
                        }
                    }
                }
            });
        }

        function handleFilterStats(q) {
            // q is d, m ,y
            $(`input#option-${q}`).prop("checked", true);
            debouncedSearch(q)
        }

        const debouncedSearch = debounce(function(searchTerm) {
            getAllStats(searchTerm)
        }, 250);
    </script>


@endsection
