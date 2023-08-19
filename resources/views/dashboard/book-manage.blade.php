@extends('layouts.dashboard')
@section('title', 'จัดการการจอง')
@section('content')
    <style>
        .box-card-list {
            border: 1px solid transparent;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem
        }

        .box-card-list:hover,
        .box-card-list.active {
            background: rgba(255, 255, 255, 0.50);
            border-color: #eee;
        }
    </style>

    <section class="content">
        <div class="row">
            <div class="col-6">
                <form id="form" class="h-100" enctype="multipart/form-data" onsubmit="handleAddCat(event)">
                    <div class="col h-100">
                        <fieldset class="scroll">
                            <legend>ข้อมูลการจอง</legend>

                            
                            <div class="mb-3 row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9">
                                    <p id="date-diff" class="border rounded"></p>
                                </div>
                            </div>

                            <div id="alert-message"></div>

                            <div class="">
                                <div class="row">
                                    <!-- Section 1 -->
                                    <div class="col-12">
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">วันที่เช็คอิน</label>
                                            <div class="col-sm-9">
                                                <input type="datetime-local" name="in_datetime" class="form-control"
                                                    onchange="calcDateDiff()">
                                                <div id="error-in-datetime" class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">วันที่เช็คเอาท์</label>
                                            <div class="col-sm-9">
                                                <input type="datetime-local" name="out_datetime" class="form-control"
                                                    onchange="calcDateDiff()">
                                                <div id="error-in-datetime" class="invalid-feedback"></div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-4">
                                              <div data-coreui-locale="en-US" data-coreui-timepicker="true" data-coreui-toggle="date-picker"></div>
                                            </div>
                                            <div class="col-lg-4">
                                              <div data-coreui-date="2023/03/15 02:22:13 PM" data-coreui-locale="en-US" data-coreui-timepicker="true" data-coreui-toggle="date-picker"></div>
                                            </div>
                                          </div>

                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">สถานะการจอง</label>
                                            <div class="col-sm-9">
                                                <select class="form-select" name="rent_status">
                                                    <option value="PENDING" selected>กำลังรอ</option>
                                                    <option value="RESERVED">จองแล้ว</option>
                                                    <option value="CHECKED_IN">เช็คอิน</option>
                                                    <option value="CHECKED_OUT">เช็คเอาท์</option>
                                                    <option value="COMPLETED">เสร็จสิ้น</option>
                                                    <option value="CANCELED">ยกเลิก</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">สถานะการจ่ายเงิน</label>
                                            <div class="col-sm-9">
                                                <select class="form-select" name="pay_status">
                                                    <option value="PENDING" selected>กำลังรอ</option>
                                                    <option value="PAYING">จ่ายแล้ว</option>
                                                    <option value="COMPLETED">เสร็จสิ้น</option>
                                                    <option value="CANCELED">ยกเลิก</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="d-flex gap-4" style="padding: 12px">
                            {{-- <button type="button" class="btn btn-secondary" onclick="handleReport()">พิมพ์รายงาน</button> --}}
                            <button type="button" class="btn btn-info" onclick="handleUpdateEmployee()">แก้ไข</button>
                            <button type="button" class="btn btn-danger" onclick="handleDeleteEmployee()">ลบ</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-6">
                <div class="">
                    <fieldset class="scroll">
                        <legend>รายการจอง</legend>
                        <div id="rent-list"></div>
                    </fieldset>
                </div>
            </div>
        </div>
    </section>

    <script>
        var selectedId = null
        var selectedIndex = null
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var storagePath = "{{ asset('storage') }}"
        var id = "{{ session('id') }}"
        var formData = null
        var search = null

        // Initialize
        $(document).ready(function() {
            handleGetAllRent()
            callSearchFunc = handleGetAllRent;

            // const inDatetime = $('input[name="in_datetime"]');
            // const outDatetime = $('input[name="out_datetime"]');
        })

        function resetForm() {
            $("#form")[0].reset();
            selectedId = null
            files.setFilePreview()
        }

        function showAlert(type, message) {
            const target = $('#alert-message')
            const color = message === 'success' ? 'text-success' : 'text-danger'
            let html = '';

            if (Array.isArray(message)) {
                message.forEach((item) => html += `<li>${item}</li>`)
            } else {
                html = message || ''
            }
            target.empty().append(`<div class="${color} font-medium mb-2"><ul>${html}</ul></div>`);
        }

        async function calcDateDiff() {

            // await delay(1000)
            // const inDatetime = $('input[name="in_datetime"]');
            // const outDatetime = $('input[name="out_datetime"]');
            // const currentDate = dayjs();
            // const dateDiff = dayjs(outDatetime.val()).diff(inDatetime.val(), 'day')

            // if (dayjs(inDatetime.val()).isBefore(currentDate, 'day')) {
            //     inDatetime.val(currentDate.format('YYYY-MM-DDTHH:mm'))
            // }

            // if (dateDiff <= 0) {
            //     outDatetime.val(dayjs(inDatetime.val()).add(1, 'day').format('YYYY-MM-DDTHH:mm'))
            // }

            // if (dayjs(outDatetime.val()).diff(inDatetime.val(), 'day') > 0) {
            //     console.log('ok')
            //     $('#date-diff').text(`จำนวน ${dayjs(outDatetime.val()).diff(inDatetime.val(), 'day')} วัน`)
            // }
        }

        function handleGetAllRent() {
            utils.setLinearLoading('open')

            $.ajax({
                url: `${prefixApi}/api/rent/list${window.location.search}`,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    if (!Array.isArray(response.data)) return

                    console.log(response)

                    let html = ''
                    response.data.forEach(function(rent, index) {
                        const dateDiff = dayjs(rent.out_datetime).diff(rent.in_datetime, 'day')
                        html += `
                        <div class="box-card-list" onclick="handleShowRent(${index} ,${utils.jsonString(rent)})">
                            <div>
                                <p>รหัสการจอง ${rent.rent_id}</p>
                                <p>รหัสสมาชิก ${rent.member_id}</p>
                                <p>ชื่อสมาชิก ${rent.member.member_name}</p>
                                <p>ชื่อห้อง ${rent.room.room_name}</p>
                                <p>วันที่เช็คอิน ${formatDate(rent.in_datetime)}</p>
                                <p>วันที่เช็คเอาท์ ${formatDate(rent.out_datetime)}</p>
                                <p>ระยะเวลา ${dateDiff}</p>
                                <p>ราคา ฿${rent.rent_price}</p>
                                <p>สถานะการจ่ายเงิน ${formatPayStatus(rent.pay_status)}</p>
                                <p>สถานะการจอง ${formatRentStatus(rent.rent_status)}</p>
                            </div>
                            <div class="border rounded bg-white" style="overflow: hidden; width: 100px; height: 100px">
                                <img id="file-preview" onerror="this.style.opacity = 0"
                                src="${storagePath}/${rent.room.room_img}"
                                style="object-fit: cover;" width="100%" height="100%">
                            </div>
                        </div>`;
                    });
                    $('#rent-list').empty().append(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error();
                }
            }).always(function() {
                utils.setLinearLoading('close')
            });
        }


        function handleShowRent(index, data) {
            const rent = JSON.parse(data)
            if (typeof rent !== 'object') return

            // console.log(rent)
            selectedId = rent.rent_id
            selectedIndex = index
            $('.box-card-list').removeClass('active').eq(index).addClass('active');
            $('select[name="rent_status"]').val(rent.rent_status || "");

            $('input[name="in_datetime"]').val(dayjs.utc(rent.in_datetime).format('YYYY-MM-DDTHH:mm'));
            $('input[name="out_datetime"]').val(dayjs.utc(rent.out_datetime).format('YYYY-MM-DDTHH:mm'));
            $('select[name="pay_status"]').val(rent.pay_status || "");
        }

        function handleUpdateEmployee() {
            if (!selectedId) return

            formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('rent_status', $('select[name="rent_status"]').val());
            formData.append('in_datetime', $('input[name="in_datetime"]').val());
            formData.append('out_datetime', $('input[name="out_datetime"]').val());
            formData.append('pay_status', $('select[name="pay_status"]').val());
            formData.append('employee_in', id);
            formData.append('employee_pay', id);

            $.ajax({
                url: `${prefixApi}/api/rent/${selectedId}`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    resetForm()
                    toastr.success();
                    handleGetAllRent()
                    handleShowRent(selectedIndex, JSON.stringify(response.data))
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    showAlert('error', response.errors)
                },
            });
        }

        async function handleDeleteEmployee() {
            if (!selectedId) return

            return

            const confirm = await utils.confirmAlert();
            if (confirm) {
                $.ajax({
                    url: `${prefixApi}/api/rent/${selectedId}`,
                    type: "DELETE",
                    headers: headers,
                    success: function(data, textStatus, jqXHR) {
                        resetForm()
                        toastr.success()
                        handleGetAllRent()
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.success()
                    },
                })
            }
        }
    </script>

@endsection
