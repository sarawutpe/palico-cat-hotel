@extends('layouts.dashboard')
@section('title', 'จัดการข้อมูลพนักงาน')
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
    </style>

    <section class="content">
        <div class="row">
            <div class="col">
                <form id="form" class="h-100" enctype="multipart/form-data" onsubmit="handleSubmit(event)">
                    <div class="col h-100">
                        <fieldset class="scroll">
                            <legend>จัดการข้อมูลพนักงาน</legend>

                            <div id="alert-message"></div>

                            <div class="">
                                <div class="row">
                                    <!-- Section 1 -->
                                    <input type="hidden" name="employee_id">
                                    <div class="col-8">
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">ชื่อผู้ใช้</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="employee_user" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">รหัสผ่าน</label>
                                            <div class="col-sm-9">
                                                <input type="password" name="employee_pass" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">ชื่อ-สกุล</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="employee_name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">ที่อยู่</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="employee_address" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">เบอร์โทรศัพท์</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="employee_phone" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label">Facebook</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="employee_facebook" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label">Line ID</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="employee_lineid" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Section 2 Upload -->
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <div class="d-flex flex-column gap-2" style="max-width: fit-content;">
                                                <div class="border rounded bg-white"
                                                    style="overflow: hidden; width: 150px; height: 150px">
                                                    <img id="file-preview" srcset="" src=""
                                                        style="object-fit: cover; opacity: 0;" width="100%"
                                                        height="100%">
                                                </div>
                                                <div class="d-flex flex-column gap-2">
                                                    <div class="btn btn-secondary position-relative w-100">
                                                        <input type="file" id="file-upload" name="employee_img"
                                                            accept="image/png, image/jpeg"
                                                            class="position-absolute opacity-0 w-100 h-100"
                                                            style="top: 0; left: 0; cursor: pointer;">
                                                        <span class="mx-1">อัพโหลด</span>
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="d-flex gap-4" style="padding: 12px">
                            <button type="button" class="btn btn-secondary" onclick="handleReport()">พิมพ์รายงาน</button>
                            <button type="button" class="btn btn-info" onclick="handleUpdateEmployee()">แก้ไข</button>
                            <button type="button" class="btn btn-danger" onclick="handleDeleteEmployee()">ลบ</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col">
                <div class="">
                    <fieldset class="scroll">
                        <legend>รายชื่อพนักงาน</legend>
                        <div id="employee-list"></div>
                    </fieldset>
                </div>
            </div>
        </div>
    </section>

    <script>
        var selectedId = null
        var selectedIndex = null
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var storagePath = "{{ asset('storage/') }}"
        var formData = null
        var search = null
        
        // Initialize
        $(document).ready(function() {
            handleGetAllEmployee()
            callSearchFunc = handleGetAllEmployee;
        })

        function resetForm() {
            $("#form")[0].reset();
            selectedId = null
            files.setFilePreview()
        }

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

        function handleSubmit(event) {
            event.preventDefault()
            handleAddEmployee()
        }

        function handleGetAllEmployee() {
            utils.setLinearLoading()
            const url = new URL(`${window.location.origin}/api/employee/list${window.location.search}`);

            $.ajax({
                url: url,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    if (!Array.isArray(response.data)) return

                    let html = ''
                    response.data.forEach(function(employee, index) {
                        html += `
                        <div class="box-card-list" onclick="handleShowEmployee(${index} ,${utils.jsonString(employee)})">
                            <div>
                                <p>รหัสพนักงาน ${employee.employee_id}</p>
                                <p>ชื่อ-สกุล ${employee.employee_name}</p>
                                <p>เบอร์โทรศัพท์ ${employee.employee_phone}</p>
                            </div>
                            <div class="border rounded bg-white" style="overflow: hidden; width: 100px; height: 100px">
                                <img id="file-preview" onerror="this.style.opacity = 0"
                                src="{{ asset('storage/') }}/${employee.employee_img}"
                                style="object-fit: cover;" width="100%" height="100%">
                            </div>
                        </div>`;
                    });
                    $('#employee-list').empty().append(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Failed to fetch employee data.');
                }
            }).always(async function() {
                await delay(1000)
                utils.setLinearLoading()
            });
        }

        function handleAddEmployee() {
            formData = new FormData();
            formData.append('employee_name', $('input[name="employee_name"]').val());
            formData.append('employee_user', $('input[name="employee_user"]').val());
            formData.append('employee_pass', $('input[name="employee_pass"]').val());
            formData.append('employee_address', $('input[name="employee_address"]').val());
            formData.append('employee_phone', $('input[name="employee_phone"]').val());
            formData.append('employee_facebook', $('input[name="employee_facebook"]').val());
            formData.append('employee_lineid', $('input[name="employee_lineid"]').val());

            const file = files.getFileUpload()
            if (file) {
                formData.append("employee_img", file);
            }

            const url = new URL(`${window.location.origin}/api/employee`);
            $.ajax({
                url: url,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    resetForm()
                    toastr.success('Successfully');
                    handleGetAllEmployee()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    showAlert('error', response.errors)
                },
            });
        }

        function handleShowEmployee(index, data) {
            const employee = JSON.parse(data)
            if (typeof employee !== 'object') return

            selectedId = employee.employee_id
            selectedIndex = index
            $('.box-card-list').removeClass('active').eq(index).addClass('active');

            $('input[name="employee_id"]').val(employee.employee_id || "");
            $('input[name="employee_name"]').val(employee.employee_name || "");
            $('input[name="employee_user"]').val(employee.employee_user || "");
            $('input[name="employee_pass"]').val("");
            $('input[name="employee_address"]').val(employee.employee_address || "");
            $('input[name="employee_phone"]').val(employee.employee_phone || "");
            $('input[name="employee_facebook"]').val(employee.employee_facebook || "");
            $('input[name="employee_lineid"]').val(employee.employee_lineid || "");

            if (employee.employee_img) {
                $('#file-preview').css('opacity', 1).attr('src', `${storagePath}/${(employee.employee_img || "")}`);
            } else {
                $('#file-preview').css('opacity', 0).attr('src', '');
            }
        }

        function handleUpdateEmployee() {
            if (!selectedId) return

            const url = new URL(`${window.location.origin}/api/employee/${selectedId}`);

            formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('employee_name', $('input[name="employee_name"]').val());
            formData.append('employee_user', $('input[name="employee_user"]').val());
            formData.append('employee_pass', $('input[name="employee_pass"]').val());
            formData.append('employee_address', $('input[name="employee_address"]').val());
            formData.append('employee_phone', $('input[name="employee_phone"]').val());
            formData.append('employee_facebook', $('input[name="employee_facebook"]').val());
            formData.append('employee_lineid', $('input[name="employee_lineid"]').val());

            const file = files.getFileUpload()
            if (file) {
                formData.append("employee_img", file);
            } else if (!file && isRemovedFile) {
                url.searchParams.set('set', 'file_null')
            }

            $.ajax({
                url: url.toString(),
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    resetForm()
                    toastr.success('Successfully');
                    handleGetAllEmployee()
                    handleShowEmployee(selectedIndex, JSON.stringify(response.data))
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    showAlert('error', response.errors)
                },
            });
        }

        async function handleDeleteEmployee() {
            if (!selectedId) return

            const url = new URL(`${window.location.origin}/api/employee/${selectedId}`);

            const confirm = await utils.confirmAlert();
            if (confirm) {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    headers: headers,
                    success: function(data, textStatus, jqXHR) {
                        resetForm()
                        toastr.success('', '')
                        handleGetAllEmployee()
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.success('', '')
                    },
                })
            }
        }
    </script>

@endsection
