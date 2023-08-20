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

        .cat-item {
            border: 2px solid #d8dbe0 !important
        }

        .cat-item.active {
            border-color: green !important;
        }
    </style>

    <section class="content">
        <div class="row">
            <div class="col-6">
                <form id="form" class="h-100" enctype="multipart/form-data">
                    <div class="col h-100">
                        <fieldset class="scroll">
                            <legend>ข้อมูลการจอง</legend>

                            <div id="alert-message"></div>

                            <div class="">
                                <div class="row">
                                    <!-- Section 1 -->
                                    <div class="col-12">
                                        <div class="pb-1">
                                            <label class="col-sm-3 col-form-label required">เลือกแมว</label>
                                            <div id="cat-list" class="custom-scroll d-flex gap-1" style="overflow: auto;">
                                            </div>
                                        </div>
                                        <br>

                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">เช็คอิน</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" class="form-check-input m-0" name="checkin_status">
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">รายละเอียดเช็คอิน</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="checkin_detail" rows="2"></textarea>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">วันที่เช็คอิน</label>
                                            <div class="col-sm-9">
                                                <input type="datetime-local" name="in_datetime" class="form-control">
                                                <div id="error-in-datetime" class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">วันที่เช็คเอาท์</label>
                                            <div class="col-sm-9">
                                                <input type="datetime-local" name="out_datetime" class="form-control">
                                                <div id="error-in-datetime" class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div data-coreui-locale="en-US" data-coreui-timepicker="true"
                                                    data-coreui-toggle="date-picker"></div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div data-coreui-date="2023/03/15 02:22:13 PM" data-coreui-locale="en-US"
                                                    data-coreui-timepicker="true" data-coreui-toggle="date-picker"></div>
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
                            <button type="button" class="btn btn-danger" onclick="handleDeleteBook()">ลบ</button>
                            <button type="button" class="btn btn-info" onclick="handleManageBook()">แก้ไข</button>
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
        var selectedRentId = null
        var selectedIndex = null
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var storagePath = "{{ asset('storage') }}"
        var id = "{{ session('id') }}"
        var formData = null
        var search = null
        var selectedCatId = null
        var selectedRent = null
        var catList = null

        // Initialize
        $(document).ready(function() {
            handleGetAllRent()
            handleGetAllCat()
            callSearchFunc = handleGetAllRent;
        })

        function resetForm() {
            $("#form")[0].reset();
            selectedRentId = null
            files.setFilePreview()
        }

        function handleGetAllRent() {
            utils.setLinearLoading('open')

            $.ajax({
                url: `${prefixApi}/api/rent/list${window.location.search}`,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    if (!Array.isArray(response.data)) return

                    let html = ''
                    response.data.forEach(function(rent, index) {
                        const dateDiff = dayjs(rent.out_datetime).diff(rent.in_datetime, 'day')
                        html += `
                        <div class="box-card-list" onclick="handleShowRent(${index}, ${utils.jsonString(rent)})">
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

        function handleGetAllCat() {
            utils.setLinearLoading('open')

            $.ajax({
                url: `${prefixApi}/api/cat/list${window.location.search}`,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    if (!Array.isArray(response.data)) return

                    catList = response.data

                    let html = ''
                    response.data.forEach(function(cat, index) {
                        html += `
                        <div class="cat-item border p-1 m-1 cursor-pointer rounded" style="width: 500px" onclick="handleCheckin(${index}, ${utils.jsonString(cat)})">
                            <div>
                                <p style="font-size: 18px">รหัส: ${cat.cat_id}</p>
                                <p style="font-size: 18px">ชื่อแมว: ${cat.cat_name}</p>
                                <p style="font-size: 18px">เจ้าของ: ${cat.member.member_name}</p>
                            </div>
                            <div class="border rounded bg-white" style="overflow: hidden; width: 100px; height: 100px">
                                <img id="file-preview" onerror="this.style.opacity = 0"
                                src="${storagePath}/${cat.cat_img}"
                                style="object-fit: cover;" width="100%" height="100%">
                            </div>
                        </div>`;
                    });
                    $('#cat-list').empty().append(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error();
                }
            }).always(async function() {
                utils.setLinearLoading('close')
            });
        }

        function handleCheckin(index, data) {
            const cat = JSON.parse(data)
            if (typeof cat !== 'object') return
            $('.cat-item').removeClass('active').eq(index).addClass('active');
            selectedCatId = cat.cat_id
        }

        function handleShowRent(index, data) {
            const rent = JSON.parse(data)
            if (typeof rent !== 'object') return

            selectedRentId = rent.rent_id
            selectedRent = rent
            selectedIndex = index

            $('.box-card-list').removeClass('active').eq(index).addClass('active');

            console.log(rent)
            if (rent.checkin) {
                selectedCatId = rent.checkin.cat_id

                $('input[name="checkin_status"]').prop('checked', rent?.checkin?.checkin_status ?? false);
                $('textarea[name="checkin_detail"]').val(rent?.checkin?.checkin_detail ?? "");
                const catIndex = catList.findIndex((item) => item.cat_id === rent.checkin.cat_id)
                if (catIndex > -1) {
                    $('.cat-item').removeClass('active').eq(catIndex).addClass('active');
                }

                $('#cat-list').animate({
                    scrollLeft: 110 * catIndex
                }, 'fast');
            } else {
                console.log('r')
                $('input[name="checkin_status"]').prop('checked', false);
                $('textarea[name="checkin_detail"]').val("");
                selectedCatId = null
                $('.cat-item').removeClass('active')
                $('#cat-list').animate({
                    scrollLeft: 0
                }, 'fast');
            }

            $('select[name="rent_status"]').val(rent.rent_status || "");
            $('input[name="in_datetime"]').val(dayjs.utc(rent.in_datetime).format('YYYY-MM-DDTHH:mm'));
            $('input[name="out_datetime"]').val(dayjs.utc(rent.out_datetime).format('YYYY-MM-DDTHH:mm'));
            $('select[name="pay_status"]').val(rent.pay_status || "");
        }

        function handleManageBook() {
            if (!selectedCatId) {
                return utils.showAlert('#alert-message', 'error', "กรุณาเลือกแมว")
            }

            handleUpdateCheckin()
            handleUpdateBook()
        }

        function handleUpdateCheckin() {
            if (!selectedCatId || !selectedRent) return

            formData = new FormData();
            formData.append('rent_id', selectedRent.rent_id);
            formData.append('cat_id', selectedCatId);
            formData.append('checkin_status', $('input[name="checkin_status"]').prop('checked') ? 1 : 0);
            formData.append('checkin_detail', $('textarea[name="checkin_detail"]').val());

            // Add checkin
            if (!selectedRent.checkin) {
                $.ajax({
                    url: `${prefixApi}/api/checkin`,
                    type: "POST",
                    headers: headers,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response, textStatus, jqXHR) {
                        utils.clearAlert('#alert-message')
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        const response = jqXHR.responseJSON
                        utils.showAlert('#alert-message', 'error', response.errors)
                    },
                });
            }

            // Update checkin
            const checkinId = selectedRent?.checkin?.checkin_id ?? ''
            if (checkinId) {
                formData.append('_method', 'PUT');
                $.ajax({
                    url: `${prefixApi}/api/checkin/${checkinId}`,
                    type: "POST",
                    headers: headers,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response, textStatus, jqXHR) {
                        utils.clearAlert('#alert-message')
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        const response = jqXHR.responseJSON
                        utils.showAlert('#alert-message', 'error', response.errors)
                    },
                });
            }
        }

        function handleUpdateBook() {
            if (!selectedRentId) return

            formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('rent_status', $('select[name="rent_status"]').val());
            formData.append('in_datetime', $('input[name="in_datetime"]').val());
            formData.append('out_datetime', $('input[name="out_datetime"]').val());
            formData.append('pay_status', $('select[name="pay_status"]').val());
            formData.append('employee_in', id);
            formData.append('employee_pay', id);

            $.ajax({
                url: `${prefixApi}/api/rent/${selectedRentId}`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    toastr.success();
                    handleGetAllRent()
                    handleShowRent(selectedIndex, JSON.stringify(response.data))
                    utils.clearAlert('#alert-message')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    utils.showAlert('#alert-message', 'error', response.errors)
                },
            });
        }

        async function handleDeleteBook() {
            if (!selectedRentId) return

            const confirm = await utils.confirmAlert();
            if (confirm) {
                $.ajax({
                    url: `${prefixApi}/api/rent/${selectedRentId}`,
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
