@extends('layouts.dashboard')
@section('title', 'การดูแลแมว')
@section('content')
    <style>
        tr:hover {
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
        }

        .zoom-img {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .zoom-img .icon {
            border-radius: 6px;
            z-index: 2;
        }

        .zoom-img:hover.zoom-img .icon {
            opacity: 1;
        }

        .zoom-img .icon {
            position: absolute;
            width: 100% !important;
            height: 100% !important;
            background: rgba(0, 0, 0, 0.5);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: all 200ms ease-in-out;
            opacity: 0;
        }

        .zoom-img .icon i {
            font-size: 1rem;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: rgba(255, 255, 255, 0.9);
        }

        .icon-img-action {
            display: flex;
        }

        .icon-input-upload {
            position: relative;
        }

        .preview-service-list-img {
            object-fit: contain;
            position: relative;
            z-index: 1;
        }
    </style>

    <section class="content">
        <div class="row">
            <div class="col">
                <div class="col h-100">
                    <fieldset class="scroll">
                        <legend id="title-legend">ข้อมูลการดูแลแมว</legend>
                        <div id="alert-message"></div>
                        <div class="mb-2">

                            <div id="nav-tab" role="tablist" style="display: none">
                                <button class="nav-link active" data-coreui-toggle="tab" data-coreui-target="#tab1"
                                    type="button" role="tab" aria-selected="true"></button>
                                <button class="nav-link" data-coreui-toggle="tab" data-coreui-target="#tab2" type="button"
                                    role="tab" aria-selected="false"></button>
                            </div>

                            <div class="tab-content">
                                {{-- Tab 1 --}}
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
                                                <th scope="col">รายการดูแลทั้งหมด</th>
                                                <th scope="col">รายการดูแลที่เสร็จ</th>
                                                <th scope="col">รายการดูแลที่เหลือ</th>
                                                <th scope="col">รายละเอียด</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rent-list"></tbody>
                                    </table>
                                </div>
                                {{-- Tab 2 --}}
                                <div class="tab-pane fade" id="tab2" role="tabpanel" tabindex="0">
                                    <div style="max-width: 800px; margin: auto">
                                        <div class="d-flex justify-content-between mb-2">
                                            <p class="font-bold">บันทึกการดูแลแมว</p>
                                            <div class="icon-button" onclick="handleCloseService()">
                                                <i class="fa-solid fa-close fa-sm align-middle"></i>
                                            </div>
                                        </div>
                                        <table class="table" style="position: relative">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">สิ่งที่ต้องทำ</th>
                                                    <th scope="col">เวลา</th>
                                                    <th scope="col">รูปภาพ</th>
                                                    <th scope="col" class="text-center">เช็คลิสต์</th>
                                                </tr>
                                            </thead>
                                            <tbody id="service-list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="service-list-img-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="service-list-zoom-img" src="" alt="" width="100%" height="350px"
                        style="object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <script>
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var storagePath = "{{ asset('storage') }}"
        var id = "{{ session('id') }}"
        var type = "{{ session('type') }}"
        var selectedRentId = null
        var selectedServiceId = null
        var selectedServiceListId = null
        var serviceLists = []

        $(document).ready(async function() {
            await handleGetAllRent()
        })

        function handleGetAllRent() {
            let url = ''
            if (type === 'MEMBER') {
                url = `${prefixApi}/api/rent/list/member/${id}`
            } else {
                url = `${prefixApi}/api/rent/list`
            }

            return new Promise((resolve, reject) => {
                utils.setLinearLoading('open')
                $.ajax({
                    url: `${prefixApi}/api/rent/list/member/${id}`,
                    type: "GET",
                    headers: headers,
                    success: function(response, textStatus, jqXHR) {
                        if (!Array.isArray(response.data)) return
                        let html = ''

                        response.data.forEach(function(rent, index) {
                            const dateDiff = dayjs(rent.out_datetime).diff(rent.in_datetime,
                                'day')

                            const serviceLists = rent.service && rent.service.service_lists ?
                                rent.service.service_lists : [];
                            const countServiceLists = serviceLists.length;
                            const countDoneServiceLists = serviceLists.filter((item) => item
                                .service_list_checked === 1).length;
                            const countInProgressServiceLists = serviceLists.filter((item) =>
                                item
                                .service_list_checked === 0).length;

                            html += `
                            <tr onclick="handleAddService('${rent.rent_id}')">
                                <th scope="row">${index + 1}</th>
                                <td>${rent.rent_id}</td>
                                <td>${formatDate(rent.rent_datetime)}</td>
                                <td>${rent.room.room_name}</td>
                                <td>${formatDate(rent.in_datetime)}</td>
                                <td>${formatDate(rent.out_datetime)}</td>
                                <td>${dateDiff} วัน</td>
                                <td><div class="badge rounded-pill text-bg-dark">${countServiceLists}</div></td>
                                <td><div class="badge rounded-pill text-bg-success">${countDoneServiceLists}</div></td>
                                <td><div class="badge rounded-pill text-bg-warning">${countInProgressServiceLists}</div></td>
                                <td>
                                    <div class="icon-button">
                                        <i class="fa-solid fa-magnifying-glass fa-xs align-middle"></i>
                                    </div>
                                </td>
                            </tr>`;
                        });
                        $('#rent-list').empty().append(html);
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

        function handleAddService(id) {
            selectedRentId = id

            utils.setLinearLoading('open')

            formData = new FormData();
            formData.append('rent_id', id);
            formData.append('service_detail', 'test');
            $.ajax({
                url: `${prefixApi}/api/service`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    selectedServiceId = response.data.service_id ?? ''
                    serviceLists = response.data?.service_lists ?? []

                    if (selectedServiceId) {
                        handleStepServiceList()
                    }
                    utils.clearAlert('#alert-message')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    utils.showAlert('#alert-message', 'error', response.errors)
                },
            }).always(function() {
                utils.setLinearLoading('close')
            });
        }

        function handleStepServiceList() {
            $('#title-legend').text('รายละเอียดการดูแลแมว');
            $('button[data-coreui-target="#tab2"]').trigger('click');
            handleDisplayServiceList()
        }

        function handleCloseService() {
            handleGetAllRent()
            $('#title-legend').text('ข้อมูลการดูแลแมว');
            $('button[data-coreui-target="#tab1"]').trigger('click');
        }

        function handleDisplayServiceList() {
            var html = ''
            serviceLists.forEach(function(serviceList, index) {
                const img = serviceList?.service_list_img ? `${storagePath}/${serviceList.service_list_img}` : ''

                html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        ${serviceList?.service_list_name ?? ''}  
                    </td>

                    <td>
                        ${serviceList?.service_list_datetime ? dayjs(serviceList?.service_list_datetime).format('HH:mm') : ''}
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <div class="zoom-img">
                                <img class="preview-service-list-img" src="${img}" width="50px" height="38px" style="opacity: 1;" onerror="this.style.opacity = 0" />
                                <div onclick="handleZoomImg(${index})" class="icon" style="border: 1px solid #ddd">
                                    <i class="fa-solid fa-magnifying-glass fa-xs align-middle"></i>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="text-center">
                        <input class="form-check-input m-0" type="checkbox" name="service_list_checked" value="" ${serviceList.service_list_checked ? 'checked' : ''} disabled>
                    </td>
                </tr>`;
            })
            $('#service-list').empty().append(html);
        }

        function handleZoomImg(index) {
            let image = $('.preview-service-list-img').eq(index);
            let src = image.attr('src');
            if (src) {
                $('#service-list-zoom-img').attr('src', src)
                setTimeout(() => {
                    $('#service-list-img-modal').modal('toggle')
                }, 100);
            }
        }
    </script>


@endsection
