@extends('layouts.dashboard')
@section('title', 'จัดการข้อมูลแมว')
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
                <form id="form" class="h-100" enctype="multipart/form-data" onsubmit="handleAddCat(event)">
                    <div class="col h-100">
                        <fieldset class="scroll">
                            <legend>ข้อมูลแมว</legend>

                            <div id="alert-message"></div>

                            <div class="">
                                <div class="row">
                                    <!-- Section 1 -->
                                    <div class="col-8">
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">ชื่อแมว</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="cat_name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">อายุแมว</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="cat_age" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">เพศแมว</label>
                                            <div class="col-sm-9">
                                                <select class="form-select" name="cat_sex">
                                                    <option selected value="">เลือกเพศแมว</option>
                                                    <option value="MALE">ตัวผู้</option>
                                                    <option value="FEMALE">ตัวเมีย</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">สีแมว</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="cat_color" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">พันธุ์แมว</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="cat_gen" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-sm-3 col-form-label">ข้อมูลเพิ่มเติม</label>
                                            <textarea name="cat_ref" class="form-control" rows="3"></textarea>
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
                                                        <input type="file" id="file-upload" name="cat_img"
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
                        <legend>รายชื่อแมว</legend>
                        <div id="cat-list"></div>
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
        var id = "{{ session('id') }}"
        var formData = null
        var search = null

        // Initialize
        $(document).ready(function() {
            handleGetAllCat()
            callSearchFunc = handleGetAllCat;
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

        function handleGetAllCat() {
            utils.setLinearLoading()

            $.ajax({
                url: `${prefixApi}/api/cat/list${window.location.search}`,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    if (!Array.isArray(response.data)) return

                    let html = ''
                    response.data.forEach(function(cat, index) {
                        html += `
                        <div class="box-card-list" onclick="handleShowCat(${index} ,${utils.jsonString(cat)})">
                            <div>
                                <p>รหัสประจำตัวแมว ${cat.cat_id}</p>
                                <p>ชื่อแมว ${cat.cat_name}</p>
                                <p>ชื่อเจ้าของสัตว์ ${cat.member.member_name}</p>
                            </div>
                            <div class="border rounded bg-white" style="overflow: hidden; width: 100px; height: 100px">
                                <img id="file-preview" onerror="this.style.opacity = 0"
                                src="{{ asset('storage/') }}/${cat.cat_img}"
                                style="object-fit: cover;" width="100%" height="100%">
                            </div>
                        </div>`;
                    });
                    $('#cat-list').empty().append(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error();
                }
            }).always(async function() {
                await delay(1000)
                utils.setLinearLoading()
            });
        }

        function handleAddCat(event) {
            event.preventDefault()

            formData = new FormData();
            formData.append('cat_name', $('input[name="cat_name"]').val());
            formData.append('cat_age', $('input[name="cat_age"]').val());
            formData.append('cat_sex', $('select[name="cat_sex"]').val());
            formData.append('cat_color', $('input[name="cat_color"]').val());
            formData.append('cat_gen', $('input[name="cat_gen"]').val());
            formData.append('cat_ref', $('textarea[name="cat_ref"]').val());
            formData.append('member_id', id);

            const file = files.getFileUpload()
            if (file) {
                formData.append("cat_img", file);
            }

            $.ajax({
                url: `${prefixApi}/api/cat`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    resetForm()
                    toastr.success();
                    handleGetAllCat()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    showAlert('error', response.errors)
                },
            });
        }

        function handleShowCat(index, data) {
            const cat = JSON.parse(data)
            if (typeof cat !== 'object') return

            selectedId = cat.cat_id
            selectedIndex = index
            $('.box-card-list').removeClass('active').eq(index).addClass('active');

            $('input[name="cat_name"]').val(cat.cat_name || "");
            $('input[name="cat_age"]').val(cat.cat_age || "");
            $('select[name="cat_sex"]').val(cat.cat_sex || "");
            $('input[name="cat_color"]').val(cat.cat_color);
            $('input[name="cat_gen"]').val(cat.cat_gen || "");
            $('textarea[name="cat_ref"]').val(cat.cat_ref || "");

            files.setFilePreview(`${storagePath}/${(cat.cat_img || "")}`)
        }

        function handleUpdateEmployee() {
            if (!selectedId) return

            formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('cat_name', $('input[name="cat_name"]').val());
            formData.append('cat_age', $('input[name="cat_age"]').val());
            formData.append('cat_sex', $('select[name="cat_sex"]').val());
            formData.append('cat_color', $('input[name="cat_color"]').val());
            formData.append('cat_gen', $('input[name="cat_gen"]').val());
            formData.append('cat_ref', $('textarea[name="cat_ref"]').val());

            const file = files.getFileUpload()
            if (file) {
                formData.append("cat_img", file);
            } else if (!file && isRemovedFile) {
                url.searchParams.set('set', 'file_null')
            }

            $.ajax({
                url: `${prefixApi}/api/cat/${selectedId}`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    resetForm()
                    toastr.success();
                    handleGetAllCat()
                    handleShowCat(selectedIndex, JSON.stringify(response.data))
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    showAlert('error', response.errors)
                },
            });
        }

        async function handleDeleteEmployee() {
            if (!selectedId) return

            const confirm = await utils.confirmAlert();
            if (confirm) {
                $.ajax({
                    url: `${prefixApi}/api/cat/${selectedId}`,
                    type: "DELETE",
                    headers: headers,
                    success: function(data, textStatus, jqXHR) {
                        resetForm()
                        toastr.success()
                        handleGetAllCat()
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.success()
                    },
                })
            }
        }
    </script>

@endsection
