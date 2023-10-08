@extends('layouts.dashboard')
@section('title', 'จัดการข้อมูลการจอง')
@section('is_search', false)
@section('content')
    <style>
        :root {
            --fc-bg-event-opacity: 0.80;
        }

        .calendar-container {
            max-width: 750px;
            background: #fff;
            padding: 1rem;
            margin: 0 auto;
        }

        #calendar {
            width: 100%;
            height: 100%;
        }

        #calendar.fc .fc-toolbar-title {
            font-size: 1.5rem;
        }

        #calendar.fc .fc-button {
            font-size: 1rem;
        }

        #calendar .fc-header-toolbar.fc-toolbar {
            padding: 0.5rem;
            margin-bottom: 0.5rem;
        }

        #calendar.fc .fc-event {
            transition: all 200ms ease-in-out;
        }

        #calendar.fc .fc-event:hover {
            opacity: 1;
        }

        #calendar.fc .fc-event-title {
            font-style: normal;
            font-size: 1.5em;
            color: #fff;
        }

        #calendar .fc-event.is-open-event {
            cursor: pointer;
            background: rgb(51, 182, 121);
        }

        #calendar .fc-event.is-close-event {
            cursor: default;
            background: rgb(244, 81, 30);
        }

        #calendar .fc-icon-select {
            color: rgb(51, 182, 121);
            opacity: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        #calendar [data-event="selected"] i.fc-icon-select {
            opacity: 1 !important;
        }

        #calendar [data-event="in-range"] i.fc-icon-select {
            opacity: 0.4 !important;
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

        .c-container {
            width: 100%;
            max-width: 850px;
        }

        .card-room-type-item {
            cursor: pointer;
            border: 1px solid transparent
        }

        .card-room-type-item.active {
            border-color: #321fdb;
        }

        .card-room-item {
            cursor: pointer;
            border: 1px solid transparent;
            opacity: 1;
            border-radius: 5px;
        }

        .card-room-item.status-out {
            cursor: not-allowed;
            border-color: rgb(244, 81, 30);
            opacity: 0.5;
        }

        .card-room-item.active {
            cursor: pointer;
            border-color: #321fdb;
        }
    </style>

    <section class="content">
        <div class="row">
            <div class="col">
                <div class="col h-100">
                    <fieldset class="">
                        <legend>ข้อมูลห้องพัก</legend>
                        {{-- Step --}}
                        <div class="d-flex w-100">
                            <div id="bar-progress" class="c-container mb-4">
                                <div class="step step-active">
                                    <span class="number-container">
                                        <span class="number">1</span>
                                    </span>
                                    <h5>ตารางการจอง</h5>
                                </div>
                                <div class="seperator"></div>
                                <div class="step">
                                    <span class="number-container">
                                        <span class="number">2</span>
                                    </span>
                                    <h5>เลือกขนาดห้องพัก</h5>
                                </div>
                                <div class="seperator"></div>
                                <div class="step">
                                    <span class="number-container">
                                        <span class="number">3</span>
                                    </span>
                                    <h5>ห้องพักทั้งหมด</h5>
                                </div>
                                <div class="seperator"></div>
                                <div class="step">
                                    <span class="number-container">
                                        <span class="number">4</span>
                                    </span>
                                    <h5>เลือกแมว</h5>
                                </div>
                                <div class="seperator"></div>
                                <div class="step">
                                    <span class="number-container">
                                        <span class="number">5</span>
                                    </span>
                                    <h5>ตรวจสอบและชำระเงิน</h5>
                                </div>
                            </div>
                        </div>
                        {{-- Message --}}
                        <div id="alert-message"></div>

                        {{-- Nav Content --}}
                        <div class="nav nav-tabs" id="nav-tabx" role="tablist" style="opacity: 0">
                            <button class="active" data-coreui-toggle="tab" data-coreui-target="#tab1" type="hidden"
                                data-index="1" role="tab">
                            </button>
                            <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab2" type="hidden"
                                data-index="2" role="tab">
                            </button>
                            <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab3" type="hidden"
                                data-index="3" role="tab">
                            </button>
                            <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab4" type="hidden"
                                data-index="4" role="tab">
                            </button>
                            <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab5" type="hidden"
                                data-index="5" role="tab">
                            </button>
                        </div>

                        {{-- Content --}}
                        <div class="tab-content">
                            {{-- Step 1 --}}
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel" tabindex="1">
                                <div class="calendar-container">
                                    <div id='calendar'></div>
                                </div>
                            </div>
                            {{-- Step 2 --}}
                            <div class="tab-pane fade" id="tab2" role="tabpanel" tabindex="2">
                                <div class="d-flex flex-wrap gap-4">
                                    @php
                                        $room_types = collect([['id' => 1, 'name' => 'ห้องเล็ก', 'room_type' => 'S', 'img' => 'assets/img/hotel-1.jpg'], ['id' => 2, 'name' => 'ห้องกลาง', 'room_type' => 'M', 'img' => 'assets/img/hotel-2.jpg'], ['id' => 3, 'name' => 'ห้องใหญ่', 'room_type' => 'L', 'img' => 'assets/img/hotel-3.jpg']])->map(function ($item) {
                                            return (object) $item;
                                        });
                                    @endphp

                                    @foreach ($room_types as $r)
                                        <div class="card card-room-type-item" style="width: 18rem;"
                                            onclick="handleStepRoomType(event, '{{ json_encode($r) }}')">
                                            <div>
                                                <img src="{{ asset($r->img) }}" class="card-img-top" width="100%"
                                                    height="180px">
                                            </div>
                                            <div class="card-body h-100 d-flex flex-column" style="flex: inherit;">
                                                <div class="mb-2 d-flex flex-column flex-1" style="flex: 1">
                                                    <h5 class="card-title"></h5>
                                                    <p class="card-text">{{ $r->name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Step 3 --}}
                            <div class="tab-pane fade" id="tab3" role="tabpanel" tabindex="3">
                                <div id="step-select-room" class="d-flex flex-wrap gap-4"></div>
                            </div>
                            {{-- Step 4 --}}
                            <div class="tab-pane fade" id="tab4" role="tabpanel" tabindex="4">
                                <p id="selected-cat-count">-</p>
                                <div class="row">
                                    <div class="col">
                                        <div id="cat-list"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- Step 5 --}}
                            <div class="tab-pane fade" id="tab5" role="tabpanel" tabindex="5">
                                <div id="step-pay"></div>
                            </div>
                        </div>

                        {{-- Next Button --}}
                        <div class="d-flex justify-content-center w-100">
                            <div class="d-flex justify-content-center w-100 my-4">
                                <div class="d-flex gap-2">
                                    <button id="prev-btn" type="button" class="btn btn-secondary opacity-0"
                                        onclick="handleStepEvent('PREV')">
                                        ก่อนหน้า
                                    </button>
                                    <button id="next-btn" type="button" class="btn btn-primary" disabled
                                        onclick="handleStepEvent('NEXT')">
                                        ถัดไป
                                    </button>
                                </div>
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
        var selectedStartDate = null
        var selectedEndDate = null
        var isSubmit = false
        var calendar = null

        // Initialize
        $(document).ready(async function() {
            await handleGetAllRent()
            await handleGetAllCat()
            handleShowCalendar()
        })

        function setIsDisabledPrevBtn(val = false) {
            if (!typeof val === 'boolean') return
            $('#prev-btn').attr('disabled', val)
        }

        function setIsDisabledNextBtn(val = false) {
            if (!typeof val === 'boolean') return
            $('#next-btn').attr('disabled', val)
        }

        function getCalendarInfo() {
            const $tableEventSelected = $('td[role="gridcell"][data-event="selected"]');
            const info = {
                start: '',
                end: ''
            }

            if ($tableEventSelected.length >= 2) {
                info.start = dayjs($tableEventSelected.eq(0).attr('data-date'))
                info.end = dayjs($tableEventSelected.eq(1).attr('data-date'))
            }
            return info
        }

        function getDaysEventOfYear() {
            // Get date beteen now to end of yaer
            // const currentDate = dayjs(); 
            const currentDate = dayjs().startOf('year');
            const endOfYear = currentDate.endOf('year');
            const days = [];

            let currentDay = currentDate;
            while (currentDay.isBefore(endOfYear)) {
                days.push(currentDay.format('YYYY-MM-DD'));
                currentDay = currentDay.add(1, 'day');
            }
            return days
        }

        function handleShowCalendar() {
            if (rents.length === 0) return

            // reset calendar
            if (calendar) {
                calendar.destroy();
            }

            // All rents
            const rentList = [...rents]
            const daysEventOfYear = getDaysEventOfYear()

            const events = daysEventOfYear.map(function(day) {
                const event = {
                    title: '',
                    start: day,
                    end: '',
                    display: 'background',
                    classNames: ['bg-white']
                }

                return event
            })

            const $calendarHtml = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar($calendarHtml, {
                locale: 'th',
                timeZone: 'local',
                initialView: 'dayGridMonth',
                selectable: true,
                showNonCurrentDates: false,
                selectOverlap: function(event) {
                    return event.rendering === 'background';
                },
                eventDidMount: function(info) {
                    $(info.el).append(`<i class='fa-regular fa-circle-check fc-icon-select'></i>`);
                },
                eventClick: function(info) {
                    const {
                        id,
                        title,
                        start,
                        end
                    } = info.event

                    let $tableEvent = $(info.el).closest('td[role="gridcell"]')
                    let $tableEventSelected = $('td[role="gridcell"][data-event="selected"]');

                    if ($tableEventSelected.length === 1) {
                        // Reset selected
                        const $start = $tableEventSelected.eq(0).attr('data-date')
                        const end = info.event.start
                        if (dayjs(end) < dayjs($start)) {
                            $('td[role="gridcell"]').attr('data-event', '');
                        }
                    }

                    if ($tableEventSelected.length >= 2) {
                        $('td[role="gridcell"]').attr('data-event', '');
                    }
                    $tableEvent.attr('data-event', 'selected');

                    // Save selected start, end
                    $tableEventSelected = $('td[role="gridcell"][data-event="selected"]');
                    selectedStartDate = $tableEventSelected.eq(0).attr('data-date')
                    selectedEndDate = $tableEventSelected.eq(1).attr('data-date')
                    if (selectedStartDate && selectedEndDate) {
                        setIsDisabledNextBtn(false)
                    } else {
                        setIsDisabledNextBtn(true)
                    }
                },
                eventMouseEnter: function(info) {
                    const {
                        id,
                        title,
                        start,
                        end
                    } = info.event
                    const $tableEvent = $(info.el).closest('td[role="gridcell"]')
                    const $tableEventSelected = $('td[role="gridcell"][data-event="selected"]');
                    if ($tableEventSelected.length === 0 || $tableEventSelected.length >= 2) return

                    let startAriaEvent = $tableEventSelected.eq(0).attr('aria-labelledby').split('-')
                    let endAriaEvent = $(info.el).closest('td[role="gridcell"]').attr('aria-labelledby').split(
                        '-')

                    if (startAriaEvent.length >= 2 && endAriaEvent.length >= 2) {
                        startAriaEvent = Number(startAriaEvent[2]) + 2
                        endAriaEvent = Number(endAriaEvent[2])
                    }

                    const $tableEventInRange = $('td[role="gridcell"][data-event="in-range"]')
                    $tableEventInRange.attr('data-event', '')

                    if ($tableEventSelected.length > 0) {
                        for (let i = startAriaEvent; i <= endAriaEvent; i += 2) {
                            $(`td[aria-labelledby="fc-dom-${i}"]`).attr('data-event', 'in-range')
                        }
                    }
                },
                eventMouseLeave: function(info) {
                    const $tableEventSelected = $('td[role="gridcell"][data-event="selected"]');
                    const $tableEventInRange = $('td[role="gridcell"][data-event="in-range"]')
                    if ($tableEventSelected.length === 1) {
                        $tableEventInRange.attr('data-event', '')
                    }
                },
                events: events
            });

            calendar.render();
        }

        function getDateDiff() {
            if (!selectedStartDate || !selectedEndDate) return

            let s = dayjs(selectedStartDate)
            let e = dayjs(selectedEndDate)
            var diff = dayjs(e).diff(s, 'day')
            return diff > 0 ? diff : 0
        }

        function handleStepEvent(action) {
            let cuiTab = Number($('.tab-pane.show.active').attr('tabindex'));
            let totalTabs = 5;

            if (action === 'PREV' && cuiTab > 1) {
                cuiTab = cuiTab - 1
                $(`button[data-coreui-target="#tab${cuiTab}"]`).tab('show');

            } else if (action === 'NEXT' && cuiTab <= totalTabs) {
                cuiTab = cuiTab + 1
                $(`button[data-coreui-target="#tab${cuiTab}"]`).tab('show');
            }

            $("#prev-btn").toggleClass('opacity-0', cuiTab === 1)
            $('.step').each(function(index, element) {
                $(element).toggleClass('step-active', index < cuiTab);
            });

            handleStepChange()
        }

        function clearTab(index) {
            if (index === 1) {
                handleShowCalendar()
            } else if (index === 2) {
                selectedRoomType = null
                $('.card-room-type-item').removeClass('active');
            } else if (index === 3) {
                selectedRoom = null
                $('.card-room-item').removeClass('active');
            } else if (index === 4) {
                selectedCatList = []
                $('#selected-cat-count').text('-');
                $('.box-cat-list').removeClass('active');
            } else if (index === 5) {}
        }

        function handleStepChange() {
            let isDisabled = true;
            let cuiTab = Number($('button[aria-selected="true"]').attr('data-index'));
            $('#next-btn').text('ถัดไป')

            setTimeout(() => {
                if (cuiTab === 1) {
                    clearTab(1)
                    clearTab(2)
                    clearTab(3)
                    clearTab(4)

                    isSubmit = false
                    isDisabled = !selectedStartDate || !selectedEndDate
                } else if (cuiTab === 2) {
                    clearTab(3)
                    clearTab(4)

                    isSubmit = false
                    isDisabled = !selectedRoomType;
                    handleGetAllRoom()
                } else if (cuiTab === 3) {
                    clearTab(4)

                    isSubmit = false
                    isDisabled = !selectedRoom;
                } else if (cuiTab === 4) {
                    isSubmit = false
                    isDisabled = !selectedCatList.length;
                } else if (cuiTab === 5) {
                    $('#next-btn').text('ยืนยันการจอง')
                    handleShowStepPay()
                    if (isSubmit) {
                        handleAddRent()
                    }
                    isDisabled = false
                    isSubmit = true
                }
                setIsDisabledNextBtn(isDisabled);
            }, 0);
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
            if (!selectedRoomType || !selectedStartDate || !selectedEndDate) return

            let t = selectedRoomType.room_type
            let s = selectedStartDate
            let e = selectedEndDate

            return new Promise((resolve, reject) => {
                utils.setLinearLoading('open')
                const url = `${prefixApi}/api/room/list?room_type=${t}&start_date=${s}&end_date=${e}`
                $.ajax({
                    url: url,
                    type: "GET",
                    headers: headers,
                    success: function(response, textStatus, jqXHR) {
                        if (!Array.isArray(response.data)) return

                        $('#step-select-room').empty()
                        let html = ''
                        let data = response.data

                        if (data.length > 0) {
                            data.forEach(function(room, index) {
                                const isReserved = room.rents.length > 0
                                html += `
                                <div onclick="handleStepRoom(event, ${isReserved}, ${utils.jsonString(room)})" class="d-flex flex-wrap gap-4 card-room-item ${isReserved ? 'status-out' : ''}">
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
                                            </div>
                                        </div>
                                </div>`;
                            });
                        } else {
                            html = `<p>ไม่พบห้อง</p>`
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
                        <div class="box-cat-list" onclick="handleSelectCat(${index}, ${utils.jsonString(cat)})">
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

            $('#selected-cat-count').text(`${selectedCatList.length}/${selectedRoom.room_limit}`)
            handleStepChange()
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
                            <p>วันที่เช็คเอาท์ <b>${dayjs(selectedStartDate).format('DD/MM/YYYY')}</b></p>
                            <p>วันที่เช็คอิน <b>${dayjs(selectedEndDate).format('DD/MM/YYYY')}</b></p>
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
                </div>
            </div>`;

            $('#step-pay').empty().append(html).ready(function() {
                fileToolkit()
            });
        }

        function handleStepRoomType(event, roomType) {
            const roomTypeObj = JSON.parse(roomType)
            if (typeof roomTypeObj !== 'object') return

            $('.card-room-type-item').removeClass('active');
            $(event.currentTarget).addClass('active');

            selectedRoomType = roomTypeObj;
            setIsDisabledNextBtn(false)
            handleGetAllRoom()
        }

        function handleStepRoom(event, isReserved, room) {
            if (isReserved || !room) return

            $('.card-room-item').removeClass('active');
            $(event.currentTarget).addClass('active');

            const roomObj = JSON.parse(room)
            if (typeof roomObj !== 'object') return

            selectedRoom = roomObj
            handleShowStepDetail()
            handleStepChange()
        }

        // Service add rent, checkin, checkin list
        async function handleAddRent() {
            try {

                const room = selectedRoom
                const inDatetime = dayjs(selectedStartDate).format();
                const outDatetime = dayjs(selectedEndDate).format();
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
                if (!rentResult.success || !rentId) throw new Error('')

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

                if (!checkinResult.success) throw new Error('')

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

                // Set loading
                utils.loading('close')
                utils.setLinearLoading('close');
                await utils.showDialog(`หมายเลขการจอง #${rentId}`, 'success')
                location.reload();
            } catch (error) {
                console.log(error)
                await utils.showDialog(`${error.responseJSON.errors.toString()}`, 'error')
                utils.loading('close')
                utils.setLinearLoading('close');
            }
        }
    </script>
@endsection
