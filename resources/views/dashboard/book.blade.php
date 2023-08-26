@extends('layouts.dashboard')
@section('title', 'จัดการข้อมูลการจอง')
@section('content')
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
                                    role="tab" aria-selected="false">สถานะห้องพัก</button>
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
                                                <button onclick="handleStepRoomType('{{ $r->room_type }}')"
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
                                        <img src="{{ asset('storage/${room.room_img}') }}" class="card-img-top"
                                            width="100%" height="">
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

        var id = "{{ session('id') }}"

        var selectedId = null
        var selectedRoomType = ''
        var selectedRoomId = ''
        var selectedRoom = ''
        var rents = []

        // Initialize
        $(document).ready(async function() {
            // Initialize the in_datetime datepicker
            const inDatetimePicker = $('input[name="in_datetime"]').datepicker({
                dateFormat: "dd/mm/yy",
                isBuddhist: true,
                showButtonPanel: true,
                onSelect: function(selectedDate) {
                    var inDateObject = $(this).datepicker('getDate');
                    var outDateObject = outDatetimePicker.datepicker('getDate');

                    if (dayjs(outDateObject).diff(inDateObject, 'day') > 0) {
                        // is ok
                    } else {
                        var minDate = dayjs(inDateObject).add('1', 'day').toDate();
                        outDatetimePicker.datepicker("option", "minDate", minDate);
                    }
                }
            });

            // Initialize the out_datetime datepicker
            const outDatetimePicker = $('input[name="out_datetime"]').datepicker({
                dateFormat: "dd/mm/yy",
                isBuddhist: true,
                showButtonPanel: true,
            });

            await handleGetAllRent()
            await handleGetAllRoom()
        })

        $('#nav-tab button').click(function() {
            event.preventDefault();
            const targetTab = $(this).attr('data-coreui-target');

            // prevent
            if (targetTab === '#tab4') {
                if (!$('input[name="in_datetime"]').val() || !$('input[name="out_datetime"]').val()) {
                    $('button[data-coreui-target="#tab3"]').tab('show');
                }
            }
        });

        function calcDateDiff() {
            const inDatetime = $('input[name="in_datetime"]');
            const outDatetime = $('input[name="out_datetime"]');
            const currentDate = dayjs();
            const dateDiff = dayjs(outDatetime.val()).diff(inDatetime.val(), 'day')

            if (dayjs(inDatetime.val()).isBefore(currentDate, 'day')) {
                inDatetime.val(currentDate.format('YYYY-MM-DDTHH:mm'))
            }

            if (dateDiff <= 0) {
                outDatetime.val(dayjs(inDatetime.val()).add(1, 'day').format('YYYY-MM-DDTHH:mm'))
            }

            if (dayjs(outDatetime.val()).diff(inDatetime.val(), 'day') > 0) {
                $('#date-diff').text(`จำนวน ${dayjs(outDatetime.val()).diff(inDatetime.val(), 'day')} วัน`)
            }
        }

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

                        let html = ''
                        let newData = response.data

                        newData = newData.filter(function(item) {
                            if (selectedRoomType) {
                                return item.room_type === selectedRoomType
                            }
                            return true
                        })

                        if (newData.length > 0) {
                            newData.forEach(function(room, index) {
                                const isBookedOut = rents.find((rent) => rent.room_id === room
                                    .room_id && rent.rent_status === 'RESERVED')

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


                        $('#step-select-room').empty().append(html);
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

        function handleShowStepDetail() {
            const room = selectedRoom
            const html = `
            `;
            $('#step-detail').empty().append(html);
        }

        function handleShowStepPay() {
            const room = selectedRoom

            const inDatetime = $('input[name="in_datetime"]');
            const outDatetime = $('input[name="out_datetime"]');
            const currentDate = dayjs();
            const dateDiff = dayjs(outDatetime.val()).diff(inDatetime.val(), 'day')

            const html = `
            <div class="row">
                <!-- Upload -->
                <div class="col d-flex justify-content-center">
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="upload-block">
                                <div class="preview-img-block"
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
                    </div>
                </div>
                <!-- Details -->
                <div class="col">
                    <h3 class="col-sm-3 col-form-label">รายการชำระเงิน</h3>
                    <div class="d-flex justify-content-between">
                        <p>1. ${selectedRoomType} (${room.room_name})</p>
                        <p>฿${room.room_price}</p>
                    </div>
                    <hr>
                    <!-- QR -->
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('assets/img/qr-payment.jpeg') }}" width="250px" height="100%" />
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="font-medium">สรุปยอด ${dateDiff} วัน</p>
                        <p class="font-medium">฿${room.room_price * dateDiff}</p>
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

        function handleStepRoomType(name) {
            if (!name) return
            selectedRoomType = name;
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

            if (inDatetime.val() && outDatetime.val()) {
                goTab(4)
                handleShowStepPay()
            }
        }

        function handleAddRent() {
            const room = selectedRoom

            const inDatetime = $('input[name="in_datetime"]').val();
            const outDatetime = $('input[name="out_datetime"]').val();
            const dateDiff = dayjs(outDatetime).diff(inDatetime, 'day')
            const roomId = room.room_id
            const rentPrice = room.room_price * dateDiff
            const file = files.getFileUpload()

            if (!file) {
                files.setMessage('error', 'กรุณาอัพโหลดสลิป')
            }

            if (!inDatetime || !outDatetime || !roomId || !file || !rentPrice) return

            utils.setLinearLoading('open')

            formData = new FormData();
            formData.append('rent_datetime', dayjs().format());
            formData.append('rent_status', '');
            formData.append('rent_price', rentPrice);
            formData.append('in_datetime', inDatetime);
            formData.append('out_datetime', outDatetime);
            formData.append('member_id', id);
            formData.append('employee_in', '');
            formData.append('employee_pay', '');
            formData.append('room_id', roomId);
            formData.append('pay_status', '');
            formData.append("slip_img", file);

            $.ajax({
                url: `${prefixApi}/api/rent`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    toastr.success();
                    handleGetAllRoom()
                },
                error: async function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    await utils.showDialog(response.errors)
                    location.reload()
                },
            }).always(async function() {
                utils.setLinearLoading('close')
            });
        }
    </script>
@endsection
