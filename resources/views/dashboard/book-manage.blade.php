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

        #cat-list {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .box-cat-list {
            position: relative;
            overflow: hidden;
            border: 1px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            margin-bottom: 0.5rem;
            width: 180px;
            height: 180px;
            border-radius: 0.375rem;
        }

        .box-cat-list img {
            object-fit: contain;
        }

        .box-cat-list.active .box-cat-icon {
            opacity: 1 !important;
        }

        .box-cat-content {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 70px;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 8px;
            font-size: 20px;
            color: #fff;
        }

        .box-cat-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            height: 70px;
            padding: 8px;
            color: #2eb85c;
            display: flex;
            align-items: center;
            opacity: 0;
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .box-cat-list:hover,
        .box-cat-list.active {
            background: rgba(255, 255, 255, 0.50);
            border-color: #eee;
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
                                                    <option value="CANCELED">ยกเลิก</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="d-flex gap-4" style="padding: 12px">
                            <button id="delete-btn" type="button" class="btn btn-danger"
                                onclick="handleDeleteBook()">ลบ</button>
                            <button id="edit-btn" type="button" class="btn btn-info"
                                onclick="handleManageRentService()">แก้ไข</button>
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
        var selectedRent = null
        var selectedCatList = []
        var selectedRoom = null

        // Initialize
        $(document).ready(function() {
            handleGetAllRent()
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
                    const data = response.data

                    let html = ''
                    var catNameHtml = ``;

                    data.forEach(function(rent, index) {
                        const dateDiff = dayjs(rent.out_datetime).diff(rent.in_datetime, 'day')

                        const catNameHtml = rent.checkin_cats.map(function(checkinCat, index) {
                            return `รหัส CAT${checkinCat.cat?.cat_id ?? ''} ${checkinCat.cat?.cat_name ?? ''}`
                        }).join(', ');

                        const isCheckin = rent?.checkin?.checkin_status

                        html += `
                        <div class="box-card-list" onclick="handleShowRent(${index}, ${utils.jsonString(rent)})">
                            <div>
                                <p>รหัสการจอง ${rent.rent_id}</p>
                                <p>รหัสสมาชิก ${rent.member_id}</p>
                                <p>ชื่อสมาชิก ${rent.member.member_name}</p>
                                <p>ชื่อห้อง ${rent.room.room_name}</p>
                                <p>จำกัดแมว ${rent.room.room_limit} ตัว</p>
                                <br>
                                <p>เช็คอิน-เอ้าท์ ${formatDate(rent.in_datetime)} - ${formatDate(rent.out_datetime)} (${dateDiff} วัน)</p>
                                <p>รายชื่อแมว ${catNameHtml}</p>
                                <p>ราคาการจอง ฿${rent.rent_price}</p>
                                <br>
                                <p>สถานะเช็คอิน: <b style="color: ${getBookingTextColor(rent?.checkin?.checkin_status)}">${isCheckin ? 'เช็คอินสำเร็จ' : 'ยังไม่เช็คอิน'}</b></p>
                                <p>สถานะการจ่ายเงิน: <b style="color: ${getBookingTextColor(rent?.pay_status)}">${formatPayStatus(rent.pay_status)}</b></p>
                                <p>สถานะการจอง: <b style="color: ${getBookingTextColor(rent?.rent_status)}">${formatRentStatus(rent.rent_status)}</b></p>
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

        async function handleGetCatByMember($id) {
            utils.setLinearLoading('open')

            await $.ajax({
                url: `${prefixApi}/api/cat/member/${id}`,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    if (!Array.isArray(response.data)) return

                    selectedCatList = response.data

                    let html = ''
                    response.data.forEach(function(cat, index) {
                        html += `
                        <div id="${cat.cat_id}" class="box-cat-list" onclick="handleSelectCat(${index} ,${utils.jsonString(cat)})">
                            <img onerror="this.style.opacity = 0"
                                src="${storagePath}/${cat.cat_img}"
                                style="object-fit: cover;" width="100%" height="100%">
                            <div class="box-cat-content">
                                <p>รหัสประจำตัวแมว ${cat.cat_id}</p>
                                <p>ชื่อแมว ${cat.cat_name}</p>
                            </div>
                            <div class="box-cat-icon">
                                <i class="fa-regular fa-circle-check fa-lg"></i>
                            </div>
                        </div>`;
                    });
                    $('#cat-list').empty().append(html);

                    utils.clearAlert('#alert-message')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error();
                }
            }).always(async function() {
                utils.setLinearLoading('close')
            });
        }

        function handleSelectCat(index, cat) {
            const catObj = JSON.parse(cat);
            if (typeof catObj !== 'object') return;

            const catId = catObj.cat_id
            const targetDiv = $(`.box-cat-list#${catId}`);
            const isSelected = targetDiv.hasClass('active');

            // Check limit room
            if (!isSelected && selectedCatList.length < selectedRoom.room_limit) {
                console.log('case 1')
                selectedCatList.push(catObj);
                targetDiv.addClass('active');
            } else {
                selectedCatList = selectedCatList.filter(item => item.cat_id !== catId);
                targetDiv.removeClass('active');
            }
        }

        async function handleShowRent(index, data) {
            const rent = typeof data === 'object' ? data : JSON.parse(data)
            if (typeof rent !== 'object') return

            await handleGetCatByMember(rent.member_id)

            // Save global values
            selectedRentId = rent.rent_id
            selectedRent = rent
            selectedIndex = index
            selectedRoom = rent.room
            selectedCatList = rent.checkin_cats

            // Set active box card list
            $('.box-card-list').removeClass('active').eq(index).addClass('active');

            // Set active class of cat list
            const boxCatListHtml = $('.box-cat-list');
            boxCatListHtml.removeClass('active');
            rent.checkin_cats.forEach(function(checkinCat) {
                boxCatListHtml.filter(`#${checkinCat.cat_id}`).addClass('active');
            });

            // Hide update data button
            if (rent.rent_status === 'CHECKED_OUT' && rent.pay_status === 'PAYING') {
                $('#delete-btn').hide();
                $('#edit-btn').hide();
            } else {
                $('#delete-btn').show();
                $('#edit-btn').show();
            }

            $('input[name="checkin_status"]').prop('checked', rent?.checkin?.checkin_status ?? false);
            $('textarea[name="checkin_detail"]').val(rent?.checkin?.checkin_detail ?? "");

            $('select[name="rent_status"]').val(rent.rent_status);
            $('input[name="in_datetime"]').val(dayjs.utc(rent.in_datetime).format('YYYY-MM-DDTHH:mm'));
            $('input[name="out_datetime"]').val(dayjs.utc(rent.out_datetime).format('YYYY-MM-DDTHH:mm'));
            $('select[name="pay_status"]').val(rent.pay_status);
        }

        async function handleManageRentService() {
            if (selectedCatList.length === 0) {
                return utils.showAlert('#alert-message', 'error', "กรุณาเลือกแมว")
            }

            utils.loading('open')
            utils.setLinearLoading('open')

            await handleUpdateCheckin()
            await handleUpdateCheckinCat()
            await handleUpdateRent()

            utils.loading('close')
            utils.setLinearLoading('close')
        }

        async function handleUpdateCheckin() {
            if (!selectedRentId || selectedCatList.length === 0) return

            formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('rent_id', selectedRentId);
            formData.append('checkin_detail', $('textarea[name="checkin_detail"]').val());
            formData.append('checkin_status', $('input[name="checkin_status"]').prop('checked') ? 1 : 0);

            const checkinId = selectedRent?.checkin?.checkin_id ?? ''
            if (!checkinId) return

            await $.ajax({
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

        async function handleUpdateCheckinCat() {
            if (!selectedRentId || selectedCatList.length === 0) return

            formData = new FormData();
            formData.append('_method', 'PUT');
            selectedCatList.forEach(function(cat) {
                formData.append('cat_id[]', cat.cat_id);
            })

            await $.ajax({
                url: `${prefixApi}/api/checkin-cat/${selectedRentId}`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
            });
        }

        async function handleUpdateRent() {
            if (!selectedRentId) return

            formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('rent_status', $('select[name="rent_status"]').val());
            formData.append('in_datetime', $('input[name="in_datetime"]').val());
            formData.append('out_datetime', $('input[name="out_datetime"]').val());
            formData.append('pay_status', $('select[name="pay_status"]').val());
            formData.append('employee_in', id);
            formData.append('employee_pay', id);

            await $.ajax({
                url: `${prefixApi}/api/rent/${selectedRentId}`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    toastr.success();
                    handleGetAllRent()
                    handleShowRent(selectedIndex, response.data)
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
