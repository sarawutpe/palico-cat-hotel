@extends('layouts.dashboard')
@section('title', 'จัดการข้อมูลการจอง')
@section('is_search', false)
@section('content')

    <style>
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
            <div class="col">
                <div class="col h-100">
                    <fieldset class="scroll">
                        <legend>ข้อมูลห้องพัก</legend>

                        <div class="mb-2">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" data-coreui-toggle="tab" data-coreui-target="#tab1"
                                    type="button" role="tab" aria-selected="true">เลือกขนาดห้องพัก</button>
                                <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab2" type="button"
                                    role="tab" aria-selected="false">ห้องพักทั้งหมด</button>
                                <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab3" type="button"
                                    role="tab" aria-selected="false" disabled
                                    style="opacity: 0">รายละเอียดการจอง</button>
                                <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab4" type="button"
                                    role="tab" aria-selected="false" disabled style="opacity: 0">ชำระเงิน</button>
                            </div>

                            <div id="alert-message"></div>
                        </div>

                        <div class="tab-content">
                            {{-- Step 1 --}}
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel" tabindex="0">
                                <div class="d-flex flex-wrap gap-4">
                                    @php
                                        $room_types = collect([['id' => 1, 'name' => 'ห้องเล็ก', 'room_type' => 'S', 'img' => 'assets/img/hotel-1.jpg'], ['id' => 2, 'name' => 'ห้องกลาง', 'room_type' => 'M', 'img' => 'assets/img/hotel-2.jpg'], ['id' => 3, 'name' => 'ห้องใหญ่', 'room_type' => 'L', 'img' => 'assets/img/hotel-3.jpg']])->map(function ($item) {
                                            return (object) $item;
                                        });
                                    @endphp

                                    @foreach ($room_types as $r)
                                        <div class="card" style="width: 18rem;">
                                            <div>
                                                <img src="{{ asset($r->img) }}" class="card-img-top" width="100%"
                                                    height="180px">
                                            </div>
                                            <div class="card-body h-100 d-flex flex-column" style="flex: inherit;">
                                                <div class="mb-2 d-flex flex-column flex-1" style="flex: 1">
                                                    <h5 class="card-title"></h5>
                                                    <p class="card-text">{{ $r->name }}</p>
                                                </div>
                                                <button onclick="handleStepRoomType('{{ json_encode($r) }}')"
                                                    class="btn btn-outline-primary w-100">เลือก</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Step 2 --}}
                            <div class="tab-pane fade" id="tab2" role="tabpanel" tabindex="0">
                                <div id="step-select-room" class="d-flex flex-wrap gap-4"></div>
                            </div>
                            {{-- Step 3 --}}
                            <div class="tab-pane fade" id="tab3" role="tabpanel" tabindex="0">
                                <div class="row">
                                    <div class="col">
                                        <div id="cat-list"></div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">รหัสสมาชิก</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="member_id" value="${id}" class="form-control"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">วันที่เช็คอิน</label>
                                            <div class="col-sm-9">
                                                <input id="in_datetime" type="text" name="in_datetime"
                                                    class="form-control" autocomplete="off">
                                                <div id="error-in-datetime" class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">วันที่เช็คเอาท์</label>
                                            <div class="col-sm-9">
                                                <input id="out_datetime" type="text" name="out_datetime"
                                                    class="form-control" autocomplete="off">
                                                <div id="error-out-datetime" class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-9">
                                                <p id="date-diff" class="border rounded"></p>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-4" style="padding: 12px">
                                            <button type="button" class="btn btn-secondary"
                                                onclick="goTab(2)">ย้อนกลับ</button>
                                            <button id="next-to-pay" type="submit" class="btn btn-primary"
                                                onclick="handleStepPay()">ถัดไป</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Step 3 --}}
                            <div class="tab-pane fade" id="tab4" role="tabpanel" tabindex="0">
                                <div id="step-pay"></div>
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
        var storagePath = "{{ asset('storage') }}"

        var sessionId = "{{ session('id') }}"
        var sessionName = "{{ session('name') }}"

        var selectedId = null
        var selectedRoomType = ''
        var selectedRoomId = ''
        var selectedRoom = ''
        var rents = []
        var selectedCatList = []

        var inDatePicker = null
        var outDatePicker = null

        // Initialize
        $(document).ready(async function() {
            await handleGetAllRent()
            await handleGetAllRoom()
            await handleGetAllCat()

            // Initialize the in_datetime datepicker
            inDatePicker = $('input[name="in_datetime"]').datepicker({
                dateFormat: "dd/mm/yy",
                isBuddhist: true,
                showButtonPanel: true,
                onSelect: function(selectedDate) {
                    calcDateDiff()
                }
            });

            // Initialize the out_datetime datepicker
            outDatePicker = $('input[name="out_datetime"]').datepicker({
                dateFormat: "dd/mm/yy",
                isBuddhist: true,
                showButtonPanel: true,
                onSelect: function(selectedDate) {
                    calcDateDiff()
                }
            });
        })

        function getInDate() {
            return inDatePicker.datepicker('getDate')
        }

        function getOutDate() {
            return outDatePicker.datepicker('getDate')
        }

        function getDateDiff() {
            var inDateObject = getInDate();
            var outDateObject = getOutDate();
            var diff = dayjs(outDateObject).diff(inDateObject, 'day')
            return diff > 0 ? diff : 0
        }

        function calcDateDiff() {
            var inDateObject = getInDate();
            var outDateObject = getOutDate();

            const minDate = dayjs(inDateObject).add('1', 'day').toDate();
            outDatePicker.datepicker("option", "minDate", minDate);

            if (!inDateObject || !outDateObject) return

            if (dayjs(outDateObject).diff(inDateObject, 'day')) {
                const minDate = dayjs(inDateObject).add('1', 'day').toDate();
                outDatePicker.datepicker("option", "minDate", minDate);
            }
            $('#date-diff').text(`จำนวน ${getDateDiff()} วัน`)
        }

        $('#nav-tab button').click(function() {
            event.preventDefault();
            const targetTab = $(this).attr('data-coreui-target');

            // prevent tab 4
            if (targetTab === '#tab4') {
                if (!selectedCatList.length || !$('input[name="in_datetime"]').val() || !$(
                        'input[name="out_datetime"]').val()) {
                    $('button[data-coreui-target="#tab3"]').tab('show');
                    handleStepPay()
                }
            }
        });

        function goTab(id) {
            if (!id) return
            $(`button[data-coreui-target="#tab${id}"]`).prop('disabled', false).css('opacity', 1).tab('show');
        }

        function handleGetAllRent() {
            return new Promise((resolve, reject) => {
                utils.setLinearLoading('open')
                $.ajax({
                    url: `${prefixApi}/api/rent/list`,
                    type: "GET",
                    headers: headers,
                    success: function(response, textStatus, jqXHR) {
                        if (!Array.isArray(response.data)) return
                        rents = response.data
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error();
                    }
                }).always(function() {
                    resolve()
                    utils.setLinearLoading('close')
                });
            });
        }

        function handleGetAllRoom() {
            if (!Array.isArray(rents)) return

            return new Promise((resolve, reject) => {
                utils.setLinearLoading('open')
                $.ajax({
                    url: `${prefixApi}/api/room/list${window.location.search}`,
                    type: "GET",
                    headers: headers,
                    success: function(response, textStatus, jqXHR) {
                        if (!Array.isArray(response.data)) return

                        $('#step-select-room').empty()

                        let html = ''
                        let newData = response.data

                        newData = newData.filter(function(item) {
                            return !selectedRoomType || item.room_type === selectedRoomType
                                .room_type
                        });

                        if (newData.length > 0) {
                            newData.forEach(function(room, index) {
                                // Check book out
                                const isBookedOut = rents.find(function(rent) {
                                    return rent.room_id === room.room_id && rent
                                        .rent_status === 'RESERVED';
                                });


                                const buttonHtml = isBookedOut ?
                                    `<div class="cursor-not-allowed"><button class="btn btn-outline-danger w-100" disabled>เต็ม</button></div>` :
                                    `<button class="btn btn-outline-primary w-100" onclick="handleStepRoom(${utils.jsonString(room)})">จอง</button>`;

                                html += `
                                <div class="d-flex flex-wrap gap-4">
                                        <div class="card" style="width: 18rem;">
                                            <div>
                                                <img src="{{ asset('storage/${room.room_img}') }}" class="card-img-top" width="100%" height="180px">
                                            </div>
                                            <div class="card-body h-100 d-flex flex-column" style="flex: inherit;">
                                                <div class="mb-2 d-flex flex-column flex-1" style="flex: 1">
                                                    <h5 class="card-title">${room.room_name}</h5>
                                                    <p class="card-text">฿ ${room.room_price}</p>
                                                    <p class="card-text">จำกัดแมว ${room.room_limit} ตัว</p>
                                                    <p>${room.room_detail}</p>
                                                </div>
                                                ${buttonHtml}
                                            </div>
                                        </div>
                                </div>`;
                            });
                        } else {
                            html = `<p>ไม่พบห้องว่าง</p>`
                        }

                        $('#step-select-room').append(html);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error();
                    }
                }).always(async function() {
                    resolve()
                    utils.setLinearLoading('close')
                });
            });
        }

        function handleGetAllCat() {
            utils.setLinearLoading('open')

            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `${prefixApi}/api/cat/member/${sessionId}`,
                    type: "GET",
                    headers: headers,
                    success: function(response, textStatus, jqXHR) {
                        if (!Array.isArray(response.data)) return

                        cats = response.data

                        let html = ''
                        response.data.forEach(function(cat, index) {
                            html += `
                        <div class="box-cat-list" onclick="handleSelectCat(${index} ,${utils.jsonString(cat)})">
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
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error();
                    }
                }).always(async function() {
                    resolve()
                    utils.setLinearLoading('close')
                });
            });
        }

        function handleSelectCat(index, cat) {
            const catObj = JSON.parse(cat);
            if (typeof catObj !== 'object') return;

            const catId = catObj.cat_id
            const targetDiv = $('.box-cat-list').eq(index);
            const isSelected = targetDiv.hasClass('active');

            // Check limit room
            if (!isSelected && selectedCatList.length < selectedRoom.room_limit) {
                selectedCatList.push(catObj);
                targetDiv.addClass('active');
            } else {
                selectedCatList = selectedCatList.filter(item => item.cat_id !== catId);
                targetDiv.removeClass('active');
            }
        }

        function handleShowStepDetail() {
            const room = selectedRoom
            $('input[name="member_id"]').val(sessionId)
        }

        function handleShowStepPay() {
            const room = selectedRoom
            const currentDate = dayjs();

            const catNameHtml = selectedCatList.map(function(cat, index) {
                return `รหัส CAT${cat.cat_id ?? ''} ${cat.cat_name ?? ''}`
            }).join(', ');

            const html = `
            <div class="row">
                <!-- Details 1 -->
                <div class="col">
                    <div class="mb-3 border border-secondary rounded p-4">

                        <div>
                            <h3 class="col-sm-3 col-form-label">รายการ</h3>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="font-medium">${room.room_name}</p>
                                    <p>${formatRoomType(selectedRoom.room_type)} ${selectedRoom.room_type}</p>
                                    <p>ราคาต่อวัน <b>฿${room.room_price}</b></p>
                                </div>
                                <div>
                                    <img src="{{ asset('storage/${room.room_img}') }}" width="100%" height="100px">
                                </div>
                            </div>

                            <p>วันที่เช็คเอาท์ <b>${dayjs(getInDate()).format('DD/MM/YYYY')}</b></p>
                            <p>วันที่เช็คอิน <b>${dayjs(getOutDate()).format('DD/MM/YYYY')}</b></p>
                            <p>ระยะเวลา <b>${getDateDiff()}</b> วัน</p>
                            
                            <br>
                            <p>รหัสสมาชิก <b>${sessionId} ${sessionName}</b></p>
                            <p>รายชื่อแมว <b>${catNameHtml}</b></p>
                        </div>
                    </div>
                </div>
                <!-- Details 2 -->
                <div class="col">
                    <h3 class="col-sm-3 col-form-label">รายการชำระเงิน</h3>
                    <div>
                        <p>1. ${selectedRoom.room_name} (${formatRoomType(selectedRoom.room_type)}) ราคาสำหรับ x ${getDateDiff()} วัน</p>
                        <div class="d-flex justify-content-between">
                            <h4 class="font-medium">ราคารวม</h4>
                            <h4 class="font-medium">฿${room.room_price * getDateDiff()}</h4>
                        </div>
                    </div>
                    <hr>
                    <!-- Section -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <!-- QR -->
                        <div>
                            <img src="{{ asset('assets/img/qr-payment.jpg') }}" width="200px" height="100%" />
                        </div>
                        <!-- Upload -->
                        <div class="upload-block">
                            <div class="preview-img-block">
                                <img id="file-preview" src="" style="opacity: 0;">
                            </div>
                            <div class="btn-img-block">
                                <div class="btn btn-secondary position-relative w-100">
                                    <input type="file" id="file-upload" name="slip_img"
                                        accept="image/png, image/jpeg"
                                        class="position-absolute opacity-0 w-100 h-100"
                                        style="top: 0; left: 0; cursor: pointer;">
                                    <span class="mx-1">สลิปโอนเงิน</span>
                                    <i class="fa-solid fa-upload fa-xs align-middle"></i>
                                </div>
                                <div class="btn btn-secondary position-relative w-100"
                                    style="display: none;">
                                    <input type="button" id="file-delete"
                                        class="position-absolute opacity-0 w-100 h-100"
                                        style="top: 0; left: 0; cursor: pointer;">
                                    <span class="mx-1">ลบ</span>
                                    <i class="fa-solid fa-trash fa-xs align-middle"></i>
                                </div>
                                <div id="file-message" class="font-medium mb-2"></div>
                            </div>
                        </div>
                    </div>          
                    <div class="d-flex gap-4" style="padding: 12px">
                        <button type="button" class="btn btn-secondary" onclick="goTab(3)">ย้อนกลับ</button>
                        <button id="next-to-pay" type="submit" class="btn btn-primary" onclick="handleAddRent()">ยืนยัน</button>
                    </div>
                </div>
            </div>`;

            $('#step-pay').empty().append(html).ready(function() {
                fileToolkit()
            });
        }

        function handleStepRoomType(roomType) {
            const roomTypeObj = JSON.parse(roomType)
            if (typeof roomTypeObj !== 'object') return

            selectedRoomType = roomTypeObj;
            goTab(2)
            handleGetAllRoom()
        }

        function handleStepRoom(room) {
            if (!room) return

            const roomObj = JSON.parse(room)
            if (typeof roomObj !== 'object') return

            selectedRoom = roomObj
            goTab(3)
            handleShowStepDetail()
        }

        function handleStepPay() {
            const inDatetime = $('input[name="in_datetime"]');
            const outDatetime = $('input[name="out_datetime"]');
            const errorInDatetime = $('#error-in-datetime');
            const errorOutDatetime = $('#error-out-datetime');

            if (!inDatetime.val()) {
                errorInDatetime.text('กรุณากรอกวันที่เช็กอิน')
                inDatetime.addClass('is-invalid');
            } else {
                inDatetime.removeClass('is-invalid');
            }

            if (!outDatetime.val()) {
                errorOutDatetime.text('กรุณากรอกวันที่เช็คเอาท์')
                outDatetime.text('กรุณากรอกวันที่เช็กอิน').addClass('is-invalid');
            } else {
                outDatetime.removeClass('is-invalid');
            }

            if (!selectedCatList.length) {
                utils.showAlert('#alert-message', 'error', 'กรุณาเลือกแมว')
            } else {
                utils.clearAlert('#alert-message')
            }

            if (selectedCatList.length > 0 && inDatetime.val() && outDatetime.val()) {
                goTab(4)
                handleShowStepPay()
            }
        }

        // Service add rent, checkin, checkin list
        async function handleAddRent() {
            try {
                const room = selectedRoom

                const inDatetime = dayjs(getInDate()).format();
                const outDatetime = dayjs(outDatePicker.datepicker('getDate')).format();
                const dateDiff = dayjs(outDatetime).diff(inDatetime, 'day')
                const roomId = room.room_id
                const rentPrice = room.room_price * dateDiff
                const file = files.getFileUpload()

                if (!file) {
                    files.setMessage('error', 'กรุณาอัพโหลดสลิป')
                }

                if (!inDatetime || !outDatetime || !roomId || !file || !rentPrice || !selectedCatList.length) return

                utils.loading('open')
                utils.setLinearLoading('open')

                formData = new FormData();
                formData.append('rent_datetime', dayjs().format());
                formData.append('rent_status', '');
                formData.append('rent_price', rentPrice);
                formData.append('in_datetime', inDatetime);
                formData.append('out_datetime', outDatetime);
                formData.append('member_id', sessionId);
                formData.append('employee_in', '');
                formData.append('employee_pay', '');
                formData.append('room_id', roomId);
                formData.append('pay_status', '');
                formData.append("slip_img", file);

                // Add Rent to db
                const rentResult = await $.ajax({
                    url: `${prefixApi}/api/rent`,
                    type: "POST",
                    headers: headers,
                    data: formData,
                    processData: false,
                    contentType: false,
                });

                const rentId = rentResult?.data?.rent_id ?? ''
                if (!rentResult.success || !rentId) throw new Error('error save rent!')

                // Add Checkin to db
                formData = new FormData();
                formData.append('rent_id', rentId);
                formData.append('checkin_status', 0);
                formData.append('checkin_detail', '');

                const checkinResult = await $.ajax({
                    url: `${prefixApi}/api/checkin`,
                    type: "POST",
                    headers: headers,
                    data: formData,
                    processData: false,
                    contentType: false,
                });

                if (!checkinResult.success) throw new Error('error save checkin!')

                // Add Checkin Cat to db
                formData = new FormData();
                formData.append('rent_id', rentId);
                selectedCatList.forEach(function(cat) {
                    formData.append('cat_id[]', cat.cat_id);
                })

                const checkinCatResult = await $.ajax({
                    url: `${prefixApi}/api/checkin-cat`,
                    type: "POST",
                    headers: headers,
                    data: formData,
                    processData: false,
                    contentType: false,
                });

                if (!checkinCatResult.success) throw new Error('error save checkin cat!')

                // Set loading
                utils.loading('close')
                utils.setLinearLoading('close');
                await utils.showDialog(`หมายเลขการจอง #${rentId}`, 'success')
                location.reload();
            } catch (error) {
                toastr.error();
            } finally {
                utils.loading('close')
                utils.setLinearLoading('close');
            }
        }
    </script>
@endsection
