@extends('layouts.dashboard')
@section('title', 'จัดการข้อมูลห้องพัก')
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
            <div class="col-6">
                <form id="form" class="h-100" enctype="multipart/form-data" onsubmit="handleAddEmployee(event)">
                    <div class="col h-100">
                        <fieldset class="scroll">
                            <legend>จัดการข้อมูลห้องพัก</legend>

                            <div id="alert-message"></div>

                            <div class="">
                                <div class="row">
                                    <!-- Section 1 -->
                                    <div class="col-8">
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">ชื่อห้อง</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="room_name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">ขนาดห้อง</label>
                                            <div class="col-sm-9">
                                                <select class="form-select" name="room_type">
                                                    <option selected value="">เลือกขนาดห้อง</option>
                                                    <option value="S">ห้องเล็ก</option>
                                                    <option value="M">ห้องเล็ก</option>
                                                    <option value="L">ห้องใหญ่</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">ราคาห้อง</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="room_price" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">จำกัดจำนวนแมว</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="room_limit" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-sm-3 col-form-label"></label>
                                            <textarea name="room_detail" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <!-- Section 2 Upload -->
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <div class="upload-block">
                                                <div class="preview-img-block">
                                                    <img id="file-preview" src="" style="opacity: 0;">
                                                </div>
                                                <div class="btn-img-block">
                                                    <div class="btn btn-secondary position-relative w-100">
                                                        <input type="file" id="file-upload" name="room_img"
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
                            {{-- <button type="button" class="btn btn-secondary" onclick="handleReport()">พิมพ์รายงาน</button> --}}
                            <button type="button" class="btn btn-info" onclick="handleUpdateEmployee()">แก้ไข</button>
                            <button type="button" class="btn btn-danger" onclick="handleDeleteEmployee()">ลบ</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-6">
                <div class="">
                    <fieldset class="scroll">
                        <legend>รายชื่อห้อง</legend>
                        <div id="room-list"></div>
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
        var storagePath = "{{ asset('storage') }}"
        var formData = null
        var search = null

        // Initialize
        $(document).ready(function() {
            handleGetAllRoom()
            callSearchFunc = handleGetAllRoom;
        })

        function resetForm() {
            $("#form")[0].reset();
            selectedId = null
            files.setFilePreview()
        }

        function handleGetAllRoom() {
            utils.setLinearLoading('open')

            $.ajax({
                url: `${prefixApi}/api/room/list${window.location.search}`,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    if (!Array.isArray(response.data)) return

                    let html = ''
                    response.data.forEach(function(room, index) {
                        html += `
                        <div class="box-card-list" onclick="handleShowRoom(${index} ,${utils.jsonString(room)})">
                            <div>
                                <p>ชื่อห้อง ${room.room_name}</p>
                                <p>ขนาดห้อง ${formatRoomType(room.room_type)}</p>
                                <p>ราคาห้อง ${room.room_price}</p>
                                <p>ข้อมูลเพิ่มเติม ${room.room_detail}</p>
                            </div>
                            <div class="border rounded bg-white" style="overflow: hidden; width: 100px; height: 100px">
                                <img id="file-preview" onerror="this.style.opacity = 0"
                                src="{{ asset('storage/') }}/${room.room_img}"
                                style="object-fit: cover;" width="100%" height="100%">
                            </div>
                        </div>`;
                    });
                    $('#room-list').empty().append(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error();
                }
            }).always(async function() {
                utils.setLinearLoading('close')
            });
        }

        function handleAddEmployee(event) {
            event.preventDefault()

            formData = new FormData();
            formData.append('room_name', $('input[name="room_name"]').val());
            formData.append('room_type', $('select[name="room_type"]').val());
            formData.append('room_price', $('input[name="room_price"]').val());
            formData.append('room_limit', $('input[name="room_limit"]').val());
            formData.append('room_detail', $('input[name="room_detail"]').val());

            const file = files.getFileUpload()
            if (file) {
                formData.append("room_img", file);
            }

            $.ajax({
                url: `${prefixApi}/api/room`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    resetForm()
                    toastr.success();
                    handleGetAllRoom()
                    utils.clearAlert('#alert-message')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    utils.showAlert('#alert-message', 'error', response.errors)
                },
            });
        }

        function handleShowRoom(index, data) {
            const room = JSON.parse(data)
            if (typeof room !== 'object') return

            selectedId = room.room_id
            selectedIndex = index
            $('.box-card-list').removeClass('active').eq(index).addClass('active');

            $('input[name="room_name"]').val(room.room_name || "");
            $('select[name="room_type"]').val(room.room_type || "");
            $('input[name="room_price"]').val(room.room_price || "");
            $('input[name="room_limit"]').val(room.room_limit || "");
            $('textarea[name="room_detail"]').val(room.room_detail || "");

            files.setFilePreview(`${storagePath}/${(room.room_img || "")}`)
        }

        function handleUpdateEmployee() {
            if (!selectedId) return

            formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('room_name', $('input[name="room_name"]').val());
            formData.append('room_type', $('select[name="room_type"]').val());
            formData.append('room_price', $('input[name="room_price"]').val());
            formData.append('room_limit', $('input[name="room_limit"]').val());
            formData.append('room_detail', $('textarea[name="room_detail"]').val());

            const url = new URL(`${prefixApi}/api/room/${selectedId}`);
            const file = files.getFileUpload()
            if (file) {
                formData.append("room_img", file);
            } else if (!file && isRemovedFile) {
                url.searchParams.set('set', 'file_null')
            }

            $.ajax({
                url: url,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    toastr.success();
                    handleGetAllRoom()
                    resetForm()
                    utils.clearAlert('#alert-message')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    utils.showAlert('#alert-message', 'error', response.errors)
                },
            });
        }

        async function handleDeleteEmployee() {
            if (!selectedId) return

            const confirm = await utils.confirmAlert();
            if (confirm) {
                $.ajax({
                    url: `${prefixApi}/api/room/${selectedId}`,
                    type: "DELETE",
                    headers: headers,
                    success: function(data, textStatus, jqXHR) {
                        resetForm()
                        toastr.success()
                        handleGetAllRoom()
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error()
                    },
                })
            }
        }
    </script>

@endsection
