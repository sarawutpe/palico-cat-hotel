@extends('layouts.dashboard')
@section('title', 'การจองของฉัน')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col">
                <div class="col h-100">
                    <fieldset class="scroll">
                        <legend>จัดการการจอง</legend>

                        <div class="mb-2">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" data-coreui-toggle="tab" data-coreui-target="#tab1"
                                    type="button" role="tab" aria-selected="true">การจอง</button>
                                <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab2" type="button"
                                    role="tab" aria-selected="false">สถานะห้องพัก</button>
                            </div>

                            <div id="alert-message"></div>
                        </div>

                        <div class="tab-content">
                            {{-- Step 1 --}}
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel" tabindex="0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">เลขที่ใบจอง</th>
                                            <th scope="col">วันที่</th>
                                            <th scope="col">ห้อง</th>
                                            <th scope="col">วันที่เช็คอิน</th>
                                            <th scope="col">วันที่เช็คเอาท์</th>
                                            <th scope="col">ระยะเวลา</th>
                                            <th scope="col">สถานะการจ่ายเงิน</th>
                                            <th scope="col">สถานะการจอง</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rent-list"></tbody>
                                </table>
                            </div>

                            {{-- Step 2 --}}
                            <div class="tab-pane fade" id="tab2" role="tabpanel" tabindex="0">
                                <div id="step-select-room" class="d-flex flex-wrap gap-4"></div>
                            </div>

                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </section>

    <script>
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var storagePath = "{{ asset('storage/') }}"
        var id = "{{ session('id') }}"

        $(document).ready(async function() {
            await handleGetAllRent()
        })

        function handleGetAllRent() {
            return new Promise((resolve, reject) => {
                utils.setLinearLoading()
                $.ajax({
                    url: `${prefixApi}/api/rent/member/${id}`,
                    type: "GET",
                    headers: headers,
                    success: function(response, textStatus, jqXHR) {
                        if (!Array.isArray(response.data)) return
                        rents = response.data

                        let html = ''

                        response.data.forEach(function(rent, index) {
                            const diff = dayjs(rent.outDatetime).diff(rent.inDatetime, 'day')
                            const displayDiff = diff === 0 ? 1 : diff

                            html += `
                        <tr>
                            <th scope="row">${index + 1}</th>
                            <td>${rent.rent_id}</td>
                            <td>${formatDate(rent.rent_datetime)}</td>
                            <td>${rent.room.room_name}</td>
                            <td>${formatDate(rent.in_datetime)}</td>
                            <td>${formatDate(rent.out_datetime)}</td>
                            <td>${displayDiff} วัน</td>
                            <td>${formatPayStatus(rent.pay_status)}</td>
                            <td>${formatRentStatus(rent.rent_status)}</td>
                        </tr>`;
                        });
                        $('#rent-list').empty().append(html);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error();
                    }
                }).always(async function() {
                    resolve()
                    await delay(1000)
                    utils.setLinearLoading()
                });
            });
        }
    </script>
@endsection
