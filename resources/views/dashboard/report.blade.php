@extends('layouts.dashboard')
@section('title', 'รายงาน')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col">
                <div class="col h-100">
                    <fieldset class="scroll">
                        <legend>รายงาน</legend>
                        <div class="list-group cursor-pointer">
                            <a class="list-group-item d-flex justify-content-between align-items-center"
                                onclick="handleReport('REPORT_EMPLOYEE')">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <i class="fa-solid fa-print fa-xs align-middle"></i>
                                    <span>รายงานพนักงาน</span>
                                </div>
                                <span class="badge bg-primary rounded-pill report-count-employee">0</span>
                            </a>
                            <a class="list-group-item d-flex justify-content-between align-items-center"
                                onclick="handleReport('REPORT_MEMBER')">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <i class="fa-solid fa-print fa-xs align-middle"></i>
                                    <span>รายงานสมาชิก</span>
                                </div>
                                <span class="badge bg-primary rounded-pill report-count-member">0</span>
                            </a>
                            <a class="list-group-item d-flex justify-content-between align-items-center"
                                onclick="handleReport('REPORT_CAT')">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <i class="fa-solid fa-print fa-xs align-middle"></i>
                                    <span>รายงานแมว</span>
                                </div>
                                <span class="badge bg-primary rounded-pill report-count-cat">0</span>
                            </a>
                            <a class="list-group-item d-flex justify-content-between align-items-center"
                                onclick="handleReport('REPORT_RENT')">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <i class="fa-solid fa-print fa-xs align-middle"></i>
                                    <span>รายงานการจอง</span>
                                </div>
                                <span class="badge bg-primary rounded-pill report-count-rent">0</span>
                            </a>
                            <a class="list-group-item d-flex justify-content-between align-items-center"
                                onclick="handleReport('REPORT_PAYMENT')">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <i class="fa-solid fa-print fa-xs align-middle"></i>
                                    <span>รายงานการชำระเงิน</span>
                                </div>
                                <span class="badge bg-primary rounded-pill report-count-rent">0</span>
                            </a>
                            <a class="list-group-item d-flex justify-content-between align-items-center"
                                onclick="handleReport('REPORT_INCOME')">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <i class="fa-solid fa-print fa-xs align-middle"></i>
                                    <span>รายงานรายรับ</span>
                                </div>
                                <span class="badge bg-primary rounded-pill report-count-rent">0</span>
                            </a>
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
        var employeeList = []
        var memberList = []
        var catList = []
        var rentList = []

        $(document).ready(function() {
            getReportData()
        });

        async function getReportData() {
            try {
                utils.loading('open', 'กำลังโหลดข้อมูล')
                utils.setLinearLoading('open')

                employeeList = await $.ajax({
                    url: `${prefixApi}/api/employee/list`,
                    type: "GET",
                    headers: headers,
                })

                memberList = await $.ajax({
                    url: `${prefixApi}/api/member/list`,
                    type: "GET",
                    headers: headers,
                })

                catList = await $.ajax({
                    url: `${prefixApi}/api/cat/list`,
                    type: "GET",
                    headers: headers,
                })

                rentList = await $.ajax({
                    url: `${prefixApi}/api/rent/list`,
                    type: "GET",
                    headers: headers,
                })

                $('.report-count-employee').text(employeeList.data?.length || 0);
                $('.report-count-member').text(memberList.data?.length || 0);
                $('.report-count-cat').text(catList.data?.length || 0);
                $('.report-count-rent').text(rentList?.length || 0);
            } catch (error) {
                console.log(error)
            } finally {
                utils.loading('close')
                utils.setLinearLoading('close')
            }
        }

        function handleReport(key) {
            if (key === 'REPORT_EMPLOYEE' && Array.isArray(employeeList.data)) {
                const tableHeader = ["รหัสสมาชิก", "ชื่อ-สกุล", "ที่อยู่", "เบอร์โทรศัพท์"];
                const tableData = employeeList.data.map(item => [
                    item?.employee_id ?? '',
                    item?.employee_name ?? '',
                    item?.employee_address ?? '',
                    item?.employee_phone ?? ''
                ]);

                const table = {
                    widths: ["auto", "*", "*", "*"],
                    body: [tableHeader, ...tableData]
                };
                printPdf('รายงานข้อมูลพนักงาน', table);
            }

            if (key === 'REPORT_MEMBER' && Array.isArray(memberList.data)) {
                const tableHeader = ["รหัสพนักงาน", "ชื่อ-สกุล", "ที่อยู่", "เบอร์โทรศัพท์"];
                const tableData = memberList.data.map(item => [
                    item?.member_id ?? '',
                    item?.member_name ?? '',
                    item?.member_address ?? '',
                    item?.member_phone ?? ''
                ]);

                const table = {
                    widths: ["auto", "*", "*", "*"],
                    body: [tableHeader, ...tableData]
                };
                printPdf('รายงานข้อมูลสมาชิก', table);
            }

            if (key === 'REPORT_CAT' && Array.isArray(catList.data)) {
                const tableHeader = ["รหัสประจำตัวแมว", "ชื่อแมว", "พันธุ์", "ชื่อเจ้าของ", "เบอร์โทรศัพท์", "ข้อมูลเพิ่มเติม"];
                const tableData = catList.data.map(item => [
                    item?.cat_id ?? '',
                    item?.cat_name ?? '',
                    item?.cat_gen ?? '',
                    item?.member?.member_name ?? '',
                    item?.member?.member_phone ?? '',
                    item?.cat_ref ?? ''
                ]);

                const table = {
                    widths: ["auto", "*", "*", "*", "*", "*"],
                    body: [tableHeader, ...tableData]
                };
                printPdf('รายงานข้อมูลแมว', table);
            }

            if (key === 'REPORT_RENT' && Array.isArray(rentList.data)) {
                const tableHeader = ["วัน/เดือน/ปี", "เลขที่ใบจอง", "ห้อง", "ชื่อสมาชิก", "โทรศํพท์", "วันที่เช็คอิน",
                    "วันที่เช็คเอาท์", "ระยะเวลา", "สถานะการจอง", "สถานะการจ่ายเงิน"
                ];

                const tableData = rentList.data.map((item, index) => {
                    return [
                        `${formatDate(item.rent_datetime)}`,
                        `${item.rent_id}`,
                        `${item.room.room_name}`,
                        `${item.member.member_name}`,
                        `${item.member.member_phone}`,
                        `${formatDate(item.in_datetime)}`,
                        `${formatDate(item.out_datetime)}`,
                        `${dayjs(item.out_datetime).diff(item.in_datetime, 'day')} วัน`,
                        `${formatRentStatus(item.rent_status)}`,
                        `${formatPayStatus(item.pay_status)}`
                    ]
                });

                const table = {
                    widths: ["auto", "*", "*", "*", "*", "*", "*", "*", "*", "*"],
                    body: [tableHeader, ...tableData],
                };

                const summarize = [{
                    text: `รายการจองทั้งหมด ${rentList.data.length} รายการ`,
                    fontSize: 15,
                    bold: true,
                    margin: [0, 10, 0, 0],
                }]

                printPdf('รายงานการจอง', table, summarize);
            }

            if (key === 'REPORT_PAYMENT' && Array.isArray(rentList.data)) {
                let pendingStatusCount = 0
                let payingStatusCount = 0
                let canceledStatusCount = 0

                const tableHeader = ["วัน/เดือน/ปี", "เลขที่ใบจอง", "ห้อง", "ชื่อสมาชิก", "โทรศํพท์", "จำนวนเงิน", "สถานะการจ่ายเงิน"];
                const tableData = rentList.data.map((item, index) => {

                    if (item.pay_status === 'PENDING') {
                        pendingStatusCount += 1
                    } else if (item.pay_status === 'PAYING') {
                        payingStatusCount += 1
                    } else {
                        canceledStatusCount += 1
                    }

                    return [
                        `${formatDate(item.rent_datetime)}`,
                        `${item.rent_id}`,
                        `${item.room.room_name}`,
                        `${item.member.member_name}`,
                        `${item.member.member_phone}`,
                        `฿${item.rent_price}`,
                        `${formatPayStatus(item.pay_status)}`
                    ]
                });

                const table = {
                    widths: ["auto", "*", "*", "*", "*", "*", "*"],
                    body: [tableHeader, ...tableData],
                };

                const summarize = [{
                        text: `รวมสถานะกำลังรอ ${pendingStatusCount} รายการ`,
                        fontSize: 15,
                        bold: true,
                        margin: [0, 10, 0, 0],
                    },
                    {
                        text: `รวมสถานะจ่ายแล้ว ${payingStatusCount} รายการ`,
                        fontSize: 15,
                        bold: true,
                    },
                    {
                        text: `รวมสถานะยกเลิก ${canceledStatusCount} รายการ`,
                        fontSize: 15,
                        bold: true,
                    },
                    {
                        text: `รวมทั้งหมด ${rentList.data.length} รายการ`,
                        fontSize: 15,
                        bold: true,
                    }
                ]

                printPdf('รายงานการชำระเงิน', table, summarize);
            }

            if (key === 'REPORT_INCOME' && Array.isArray(rentList.data)) {
                let totalRentRoomSPriceList = []
                let totalRentRoomMPriceList = []
                let totalRentRoomLPriceList = []
                let totalRentPrice = 0

                const tableHeader = ["วัน/เดือน/ปี", "ขนาดห้อง", "ราคาห้อง", "จำนวนวันเข้าพัก", "จำนวนสุทธื"];

                const rentCompletedList = rentList.data.filter(function(item) {
                    return item.rent_status === 'CHECKED_OUT' && item.pay_status === 'PAYING'
                })

                const tableData = rentCompletedList.map((item, index) => {
                    const rentPrice = Number(item.rent_price)
                    if (item.room.room_type === 'S') {
                        totalRentRoomSPriceList.push(rentPrice)
                    } else if (item.room.room_type === 'M') {
                        totalRentRoomMPriceList.push(rentPrice)
                    } else {
                        totalRentRoomLPriceList.push(rentPrice)
                    }
                    totalRentPrice += rentPrice

                    return [
                        `${formatDate(item.rent_datetime)}`,
                        `${formatRoomType(item.room.room_type)} ${item.room.room_type}`,
                        `฿${item.room.room_price}`,
                        `${dayjs(item.out_datetime).diff(item.in_datetime, 'day')} วัน`,
                        `฿${item.rent_price}`
                    ]
                });

                const table = {
                    widths: ["auto", "*", "*", "*", "*"],
                    body: [tableHeader, ...tableData],
                };

                const summarize = [{
                        text: `จำนวน${formatRoomType('S')} S ${totalRentRoomSPriceList.length} รายการ ทั้งหมด ฿${totalRentRoomSPriceList.reduce((prev, cur) => prev + cur, 0)}`,
                        fontSize: 15,
                        bold: true,
                        margin: [0, 10, 0, 0],
                    },
                    {
                        text: `จำนวน${formatRoomType('M')} M ${totalRentRoomMPriceList.length} รายการ ทั้งหมด ฿${totalRentRoomMPriceList.reduce((prev, cur) => prev + cur, 0)}`,
                        fontSize: 15,
                        bold: true,
                    },
                    {
                        text: `จำนวน${formatRoomType('L')} L ${totalRentRoomLPriceList.length} รายการ ทั้งหมด ฿${totalRentRoomLPriceList.reduce((prev, cur) => prev + cur, 0)}`,
                        fontSize: 15,
                        bold: true,
                    },
                    {
                        text: `รวม ${rentCompletedList.length} รายการ รวมทั้งหมด ฿${totalRentPrice}`,
                        fontSize: 15,
                        bold: true,
                    }
                ]

                printPdf('รายงานรายรับของร้าน', table, summarize);
            }
        }
    </script>
@endsection
