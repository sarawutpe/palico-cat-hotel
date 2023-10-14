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
                                    <div style="max-width: 900px; margin: auto">
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
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="service-list"></tbody>
                                        </table>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn mt-2" onclick="handleAddServiceList()">
                                                <div class="d-flex justify-content-center align-items-center gap-1">
                                                    <p>เพิ่มรายการ</p>
                                                    <i class="fa-solid fa-plus fa-xs align-middle"></i>
                                                </div>
                                            </button>
                                        </div>
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
        var selectedServiceListIndex = null
        var serviceLists = []

        $(document).ready(async function() {
            await handleGetAllRent()
        })

        function handleGetAllRent() {
            let url = `${prefixApi}/api/rent/list`
            return new Promise((resolve, reject) => {
                utils.setLinearLoading('open')
                $.ajax({
                    url: url,
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
            $('#title-legend').text('จัดการการดูแลแมว');
            $('button[data-coreui-target="#tab2"]').trigger('click');
            handleDisplayServiceList()
        }

        function handleCloseService() {
            handleGetAllRent()
            $('#title-legend').text('ข้อมูลการดูแลแมว');
            $('button[data-coreui-target="#tab1"]').trigger('click');
        }

        async function handleAddServiceList() {
            try {
                const response = await serviceAddServiceList()
                const serviceListId = response.data.service_list_id
                serviceLists.push({
                    service_list_id: serviceListId,
                    service_list_name: "",
                    service_list_datetime: "",
                    service_list_checked: false
                })
                handleDisplayServiceList()
            } catch (error) {
                toastr.error();
            }
        }

        async function handleRemoveServiceList(index, serviceListId) {
            try {
                selectedServiceListId = serviceListId
                serviceLists.splice(index, 1)
                handleDisplayServiceList();
                await serviceDeleteServiceList()
            } catch (error) {
                toastr.error();
            }
        }

        function handleDisplayServiceList() {
            var html = ''
            serviceLists.forEach(function(serviceList, index) {
                const img = serviceList?.service_list_img ? `${storagePath}/${serviceList.service_list_img}` : ''

                html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <input class="form-control form-change p-1" type="text" name="service_list_name" value="${serviceList?.service_list_name ?? ''}" oninput="formChange(${index}, ${serviceList.service_list_id}, event)">    
                    </td>
                    <td>
                        <input class="form-control p-1" type="time" name="service_list_datetime" value="${serviceList?.service_list_datetime ? dayjs(serviceList?.service_list_datetime).format('HH:mm') : ''}" oninput="formChange(${index}, ${serviceList.service_list_id}, event)">
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <div class="zoom-img">
                                <img class="preview-service-list-img" src="${img}" width="50px" height="38px" style="opacity: 1;" onerror="this.style.opacity = 0" />
                                <div onclick="handleZoomImg(${index})" class="icon" style="border: 1px solid #ddd">
                                    <i class="fa-solid fa-magnifying-glass fa-xs align-middle"></i>
                                </div>
                            </div>
                            <div class="icon-img-action">
                                <div class="icon-button" style="position: relative; overflow: hidden;">
                                    <i class="fa-solid fa-upload fa-xs align-middle"></i>
                                    <input type="file" name="service_list_img" accept="image/png, image/jpeg" oninput="formChange(${index}, ${serviceList.service_list_id}, event)" style="opacity: 0; position: absolute;" />
                                </div>
                                <div class="icon-button" onclick="handleRemoveServiceListImg(${index}, ${serviceList.service_list_id})">
                                    <i class="fa-solid fa-trash fa-xs align-middle"></i>
                                 </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <input class="form-check-input m-0" type="checkbox" name="service_list_checked" value="" ${serviceList.service_list_checked ? 'checked' : ''} oninput="formChange(${index}, ${serviceList.service_list_id}, event)">
                    </td>
                    <td>
                        <p class="text-danger select-none" onclick="handleRemoveServiceList(${index}, ${serviceList.service_list_id})">ลบรายการ</p>
                    </td>
                </tr>`;
            })
            $('#service-list').empty().append(html);
        }

        function formChange(index, serviceListId, event) {
            const input = event.target;
            const name = input.name;
            selectedServiceListId = serviceListId
            selectedServiceListIndex = index

            if (name === 'service_list_name') {
                serviceLists[index].service_list_name = input.value || ''
            }

            if (name === 'service_list_datetime') {
                const [newHours, newMinutes] = input.value.split(':').map(Number);
                const targetDate = serviceLists[index].service_list_datetime || dayjs().format()
                const newValue = dayjs.utc(targetDate)
                    .hour(newHours)
                    .minute(newMinutes)
                serviceLists[index].service_list_datetime = newValue
            }

            if (name === 'service_list_img') {
                const fileInput = event.target;
                if (fileInput && fileInput.files) {
                    const file = fileInput.files[0];
                    const imageURL = URL.createObjectURL(file);
                    // Set preview
                    $('.preview-service-list-img').eq(index).attr("src", imageURL).css('opacity', 1);
                    // Set file
                    serviceLists[index].service_list_img = file
                }
            }

            if (name === 'service_list_checked') {
                serviceLists[index].service_list_checked = input.checked
            }

            debounceSubmit()
        }

        const debounceSubmit = debounce(function() {
            try {
                if (!selectedServiceListId) return
                serviceUpdateServiceList()
            } catch (error) {
                toastr.error();
            }
        }, 100);

        async function serviceAddServiceList() {
            if (!selectedServiceId) return;

            const formData = new FormData();
            formData.append('service_id', selectedServiceId);
            formData.append('service_list_name', '');
            formData.append('service_list_datetime', '');
            formData.append('service_list_checked', 0);

            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `${prefixApi}/api/service-list`,
                    type: 'POST',
                    headers: headers,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response, textStatus, jqXHR) {
                        resolve(jqXHR.responseJSON);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        reject(jqXHR.responseJSON);
                    },
                }).always(function() {});
            });
        }

        async function serviceUpdateServiceList() {
            if (!selectedServiceListId) return;

            // Case normal
            const serviceList = serviceLists.find(function(serviceList) {
                return serviceList.service_list_id === selectedServiceListId
            })
            if (!serviceList) return;

            return new Promise((resolve, reject) => {
                formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('service_list_name', serviceList?.service_list_name ?? '');
                formData.append('service_list_datetime', serviceList?.service_list_datetime ?? '');

                // Update img
                if (serviceList?.service_list_img) {
                    formData.append('service_list_img', serviceList?.service_list_img ?? '');
                }
                formData.append('service_list_checked', serviceList.service_list_checked ? 1 : 0);
                $.ajax({
                    url: `${prefixApi}/api/service-list/${selectedServiceListId}`,
                    type: "POST",
                    headers: headers,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response, textStatus, jqXHR) {
                        resolve(jqXHR.responseJSON);

                        const img = jqXHR.responseJSON.data.service_list_img
                        if (!img) return
                        $('.preview-service-list-img').eq(selectedServiceListIndex).attr("src",
                            `${storagePath}/${img}`).css('opacity', 1);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        reject(jqXHR.responseJSON);
                    },
                }).always(function() {});
            });
        }

        async function serviceDeleteServiceList() {
            if (!selectedServiceListId) return;
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `${prefixApi}/api/service-list/${selectedServiceListId}`,
                    type: "DELETE",
                    headers: headers,
                    success: function(data, textStatus, jqXHR) {
                        resolve(jqXHR.responseJSON);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        reject(jqXHR.responseJSON);
                    },
                }).always(function() {});
            })
        }

        function handleRemoveServiceListImg(index, id) {
            const preview = $('.preview-service-list-img').eq(index).attr("src")
            if (!preview) return

            selectedServiceListId = id
            $('.preview-service-list-img').eq(index).attr("src", '').css('opacity', 0);
            serviceLists[index].service_list_img = ''

            new Promise((resolve, reject) => {
                formData = new FormData();
                formData.append('_method', 'PUT');
                $.ajax({
                    url: `${prefixApi}/api/service-list/${selectedServiceListId}?set=file_null`,
                    type: "POST",
                    headers: headers,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response, textStatus, jqXHR) {
                        resolve(jqXHR.responseJSON);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        reject(jqXHR.responseJSON);
                    },
                }).always(function() {

                });
            });
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
