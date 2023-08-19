@extends('layouts.dashboard')
@section('title', 'การดูแลแมว')
@section('content')
    <style>
        tr:hover {
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
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
                                                <th scope="col">สถานะการจ่ายเงิน</th>
                                                <th scope="col">สถานะการจอง</th>
                                                <th scope="col">การดูแลแมว</th>
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

    <script>
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var storagePath = "{{ asset('storage') }}"
        var id = "{{ session('id') }}"
        var selectedRentId = null
        var selectedServiceId = null
        var selectedServiceListId = null
        var serviceLists = []

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

        $(document).ready(async function() {
            await handleGetAllRent()
        })

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
                        let html = ''
                        response.data.forEach(function(rent, index) {
                            const dateDiff = dayjs(rent.outDatetime).diff(rent.inDatetime,
                                'day')
                            html += `
                            <tr onclick="handleAddService('${rent.rent_id}')">
                                <th scope="row">${index + 1}</th>
                                <td>${rent.rent_id}</td>
                                <td>${formatDate(rent.rent_datetime)}</td>
                                <td>${rent.room.room_name}</td>
                                <td>${formatDate(rent.in_datetime)}</td>
                                <td>${formatDate(rent.out_datetime)}</td>
                                <td>${dateDiff} วัน</td>
                                <td>${formatPayStatus(rent.pay_status)}</td>
                                <td>${formatRentStatus(rent.rent_status)}</td>
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
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    showAlert('error', response.errors)
                },
            }).always(function() {
                utils.setLinearLoading('close')
            });
        }

        function handleStepServiceList() {
            $('#title-legend').text('จัดการการดูแลแมว');
            $('button[data-coreui-target="#tab2"]').trigger('click');
            handleAddServiceList()
        }

        function handleCloseService() {
            $('#title-legend').text('ข้อมูลการดูแลแมว');
            $('button[data-coreui-target="#tab1"]').trigger('click');
        }

        function handleAddServiceList() {
            serviceLists.push({
                service_list_id: "1",
                service_list_name: "",
                service_list_datetime: "",
                service_list_checked: false
            })
            handleDisplayServiceList()
        }

        function handleRemoveServiceList(index) {
            serviceLists.splice(index, 1)
            handleDisplayServiceList();
        }

        function handleDisplayServiceList() {
            var html = ''
            serviceLists.forEach(function(serviceList, index) {
                html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <input class="form-control form-change p-1" type="text" name="service_list_name" value="${serviceList.service_list_name}" oninput="formChange(${index}, ${serviceList.service_list_id}, event)">    
                    </td>
                    <td>
                        <input class="form-control p-1" type="time" name="service_list_datetime" value="${dayjs(serviceList.service_list_datetime).format('HH:mm')}" oninput="formChange(${index}, ${serviceList.service_list_id}, event)">
                    </td>
                    <td class="text-center">
                        <input class="form-check-input m-0" type="checkbox" name="service_list_checked" value="" ${serviceList.service_list_checked ? 'checked' : ''} oninput="formChange(${index}, ${serviceList.service_list_id}, event)">
                    </td>
                    <td>
                        <p class="text-danger select-none" onclick="handleRemoveServiceList(${index})">ลบรายการ</p>
                    </td>
                </tr>`;
            })
            $('#service-list').empty().append(html);
        }

        function formChange(index, serviceListId, event) {
            const input = event.target;
            const name = input.name;
            selectedServiceListId = serviceListId

            if (name === 'service_list_name' && serviceLists[index].service_list_name != null) {
                serviceLists[index].service_list_name = input.value
            }

            if (name === 'service_list_datetime' && serviceLists[index].service_list_datetime != null) {
                const [newHours, newMinutes] = input.value.split(':').map(Number);
                const newValue = dayjs.utc(serviceLists[index].service_list_datetime)
                    .hour(newHours)
                    .minute(newMinutes);
                serviceLists[index].service_list_datetime = newValue
            }

            if (name === 'service_list_checked' && serviceLists[index].service_list_checked != null) {
                serviceLists[index].service_list_checked = input.checked
            }

            debounceSubmit()
        }

        const debounceSubmit = debounce(function() {
            if (!selectedServiceListId) return

            // Update service list
            const serviceList = serviceLists.find(function(serviceList) {
                return serviceList.service_list_id === selectedServiceListId
            })

            if (serviceList) {
                formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('service_list_name', serviceList.service_list_name);
                formData.append('service_list_datetime', serviceList.service_list_datetime);
                formData.append('service_list_checked', serviceList.service_list_checked ? 1 : 0);

                $.ajax({
                    url: `${prefixApi}/api/service-list/${selectedServiceListId}`,
                    type: "POST",
                    headers: headers,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response, textStatus, jqXHR) {},
                    error: function(jqXHR, textStatus, errorThrown) {
                        const response = jqXHR.responseJSON
                        showAlert('error', response.errors)
                    },
                });
            }
        }, 200);


        function j() {


        }
    </script>
@endsection
