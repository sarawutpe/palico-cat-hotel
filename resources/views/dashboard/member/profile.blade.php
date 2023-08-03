@extends('layouts.dashboard')
@section('title', 'จัดการข้อมูลโปรไฟล์')
@section('content')

    <section class="content">
        <div class="container-lg">
            <div class="mx-auto" style="max-width: 768px;">

                <div id="alert-message"></div>

                <form enctype="multipart/form-data" onsubmit="handleSubmit(event)">
                    <div class="row">
                        <!-- Section 1 -->
                        <div class="col">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label required">ชื่อผู้ใช้</label>
                                <div class="col-sm-9">
                                    <input type="text" name="user" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label required">รหัสผ่าน</label>
                                <div class="col-sm-9">
                                    <input type="password" name="pass" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label required">ชื่อ-สกุล</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label required">ที่อยู่</label>
                                <div class="col-sm-9">
                                    <input type="text" name="address" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label required">เบอร์โทรศัพท์</label>
                                <div class="col-sm-9">
                                    <input type="text" name="phone" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Facebook</label>
                                <div class="col-sm-9">
                                    <input type="text" name="facebook" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Line ID</label>
                                <div class="col-sm-9">
                                    <input type="text" name="lineid" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- Section 2 Upload -->
                        <div class="col">
                            <div class="mb-3">
                                <div class="d-flex flex-column gap-2" style="max-width: fit-content;">
                                    <div class="border rounded bg-white"
                                        style="overflow: hidden; width: 250px; height: 250px">
                                        <img id="file-preview" src="" style="object-fit: cover; opacity: 0;"
                                            width="100%" height="100%">
                                    </div>
                                    <div class="d-flex gap-2">
                                        <div class="btn btn-light position-relative w-100">
                                            <input type="file" id="file-upload" name="img"
                                                accept="image/png, image/jpeg"
                                                class="position-absolute opacity-0 w-100 h-100"
                                                style="top: 0; left: 0; cursor: pointer;">
                                            <span class="mx-1">อัพโหลด</span>
                                            <i class="fa-solid fa-upload fa-xs align-middle"></i>
                                        </div>
                                        <div class="btn btn-light position-relative w-100" style="display: none;">
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
                        <!-- Image -->
                        <div class="text-center">
                            <img src="" class="rounded">
                        </div>
                    </div>
                    <!-- Button Group -->
                    <div class="d-flex gap-4">
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </section>


    <script>
        var selectedIndex = null
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var storagePath = "{{ asset('storage/') }}"
        var formData = null
        var search = null

        var id = "{{ session('id') }}"
        var type = "{{ session('type') }}"

        // Initialize
        $(document).ready(function() {
            getProfile()
        })

        function getProfile() {
            utils.setLinearLoading()
            $.ajax({
                url: `${prefixApi}/api/authen/profile/${type}/${id}`,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    const value = response.data

                    $('input[name="name"]').val(value.member_name || value.employee_name || value.admin_name);
                    $('input[name="user"]').val(value.member_user || value.employee_user || value.admin_user);
                    $('input[name="pass"]').val("");
                    $('input[name="address"]').val(value.member_address || value.employee_address || value
                        .admin_address);
                    $('input[name="phone"]').val(value.member_phone || value.employee_phone || value
                        .admin_phone);
                    $('input[name="facebook"]').val(value.member_facebook || value.employee_facebook || value
                        .admin_facebook);
                    $('input[name="lineid"]').val(value.member_lineid || value.employee_lineid || value
                        .admin_lineid);

                    files.setFilePreview(`${storagePath}/${value.member_img}`)

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Failed');
                }
            }).always(async function() {
                await delay(1000)
                utils.setLinearLoading()
            });
        }

        function handleSubmit(event) {
            event.preventDefault()

            formData = new FormData();
            formData.append('name', $('input[name="name"]').val());
            formData.append('user', $('input[name="user"]').val());
            formData.append('pass', $('input[name="pass"]').val());
            formData.append('address', $('input[name="address"]').val());
            formData.append('phone', $('input[name="phone"]').val());
            formData.append('facebook', $('input[name="facebook"]').val());
            formData.append('lineid', $('input[name="lineid"]').val());

            const file = files.getFileUpload()
            if (file) {
                formData.append("img", file);
            }

            $.ajax({
                url: `${prefixApi}/api/authen/profile/${type}/${id}`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    toastr.success('Successfully');
                    getProfile()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    utils.showAlert('#alert-message', 'error', response.errors)
                },
            });
        }
    </script>
@endsection
