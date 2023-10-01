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
            color: #fff;
            position: absolute;
            left: 50%;
            transform: translate(-50%, -70%);
            opacity: 0;
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
    </style>

    <section class="content">
        <div class="row">
            <div class="col">
                <div class="col h-100">
                    <fieldset class="">
                        <legend>ข้อมูลห้องพัก</legend>
                        {{-- Step --}}
                        <div class="d-flex justify-content-center w-100">
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
                                    <h5>รายละเอียดการจอง</h5>
                                </div>
                                <div class="seperator"></div>
                                <div class="step">
                                    <span class="number-container">
                                        <span class="number">5</span>
                                    </span>
                                    <h5>ชำระเงิน</h5>
                                </div>
                            </div>
                        </div>
                        {{-- Message --}}
                        <div id="alert-message"></div>

                        {{-- Nav Content --}}
                        <div class="nav nav-tabs" id="nav-tabx" role="tablist" style="opacity: 0">
                            <button class="active" data-coreui-toggle="tab" data-coreui-target="#tab1" type="hidden"
                                role="tab">
                            </button>
                            <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab2" type="hidden"
                                role="tab">
                            </button>
                            <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab3" type="hidden"
                                role="tab">
                            </button>
                            <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab4" type="hidden"
                                role="tab">
                            </button>
                            <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab5" type="hidden"
                                role="tab">
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
                            {{-- Step 3 --}}
                            <div class="tab-pane fade" id="tab3" role="tabpanel" tabindex="3">
                                <div id="step-select-room" class="d-flex flex-wrap gap-4"></div>
                            </div>
                            {{-- Step 4 --}}
                            <div class="tab-pane fade" id="tab4" role="tabpanel" tabindex="4">
                                <div class="row">
                                    <div class="col">
                                        <div id="cat-list"></div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">รหัสสมาชิก</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="member_id" value="${id}"
                                                    class="form-control" disabled>
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
                            <div class="c-container d-flex justify-content-end w-100 my-4">
                                <div class="d-flex gap-1">
                                    <button id="prev-btn" type="button" class="btn btn-secondary opacity-0"
                                        onclick="handleStepEvent('PREV')">
                                        ก่อนหน้า
                                    </button>
                                    <button id="next-btn" type="button" class="btn btn-primary"
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
        var inDatePicker = null
        var outDatePicker = null
        var selectedStartDate = null
        var selectedEndDate = null

        // Initialize
        $(document).ready(async function() {
            await handleGetAllRent()
            await handleGetAllRoom()
            await handleGetAllCat()

            handleShowCalendar()
            initDatepicker()
        })

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

            // All rents
            const rentList = [...rents]
            const daysEventOfYear = getDaysEventOfYear()

            const events = daysEventOfYear.map(function(day) {
                const event = {
                    title: 'ว่าง',
                    start: day,
                    end: '',
                    display: 'background',
                    classNames: ['is-open-event']
                }
                let rent = rentList.find((item) => dayjs(item.rent_datetime).format('YYYY-MM-DD') === day)

                if (rent) {
                    event.title = 'เต็ม'
                    event.start = dayjs(rent.rent_datetime).format('YYYY-MM-DD')
                    event.end = dayjs(rent.out_datetime).format('YYYY-MM-DD')
                    event.classNames = ['is-close-event']
                }

                return event
            })

            const $calendarHtml = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar($calendarHtml, {
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
                    if (title === 'เต็ม') return

                    const $tableEvent = $(info.el).closest('td[role="gridcell"]')
                    const $tableEventSelected = $('td[role="gridcell"][data-event="selected"]');

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

        function initDatepicker() {
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
        }

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

        function handleStepEvent(action) {
            console.log('called');

            let $activeTabPane = $('.tab-pane.show.active');
            let cuiTab = Number($('.tab-pane.show.active').attr('tabindex'));
            let totalTabs = 5;

            if (action === 'PREV' && cuiTab > 1) {
                cuiTab = cuiTab - 1
                $(`button[data-coreui-target="#tab${cuiTab}"]`).tab('show');
            } else if (action === 'NEXT' && cuiTab < totalTabs) {
                cuiTab = cuiTab + 1
                $(`button[data-coreui-target="#tab${cuiTab}"]`).tab('show');
            }

            $("#prev-btn").toggleClass('opacity-0', cuiTab === 1)
            $('.step').each(function(index, element) {
                $(element).toggleClass('step-active', index < cuiTab);
            });
        }

        function goTab(id) {
            if (!id) return
            // $(`button[data-coreui-target="#tab${id}"]`).prop('disabled', false).css('opacity', 1).tab('show');
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
                                // const isBookedOut = rents.find(function(rent) {
                                //     return rent.room_id === room.room_id && rent
                                //         .rent_status === 'RESERVED';
                                // });
                                const isBookedOut = true

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
                                                <button class="btn btn-outline-primary w-100" onclick="handleStepRoom(${utils.jsonString(room)})">จอง</button>
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
