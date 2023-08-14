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

                            <div id="alert-message"></div>

                            <div class="">
                                <div class="row">
                                    <!-- Section 1 -->
                                    <div class="col-12">
                                        {{-- <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required"></label>
                                            <div class="col-sm-9">
                                                <input type="text" name="" class="form-control">
                                            </div>
                                        </div> --}}
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

        function handleGetAllRent() {
            utils.setLinearLoading('open')

            $.ajax({
                url: `${prefixApi}/api/rent/list${window.location.search}`,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    if (!Array.isArray(response.data)) return


                    console.log(response.data)


                    let html = ''
                    response.data.forEach(function(rent, index) {
                        const dateDiff = dayjs(rent.outDatetime).diff(rent.inDatetime, 'day')
                        html += `
                        <div class="box-card-list" onclick="handleShowRent(${index} ,${utils.jsonString(rent)})">
                            <div>
                                <p>รหัสการจอง ${rent.rent_id}</p>
                                <p>รหัสสมาชิก ${rent.member_id}</p>
                                <p>ชื่อสมาชิก ${rent.member.member_name}</p>
                                <p>ชื่อห้อง ${rent.room.room_name}</p>
                                <p>ระยะเวลา ${dateDiff}</p>
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

            selectedId = rent.rent_id
            selectedIndex = index
            $('.box-card-list').removeClass('active').eq(index).addClass('active');
            $('select[name="rent_status"]').val(rent.rent_status || "");
            $('select[name="pay_status"]').val(rent.pay_status || "");
        }

        function handleUpdateEmployee() {
            if (!selectedId) return

            formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('rent_status', $('select[name="rent_status"]').val());
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
