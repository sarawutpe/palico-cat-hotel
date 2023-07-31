@extends('layouts.dashboard')
@section('title', 'จัดการข้อมูลโปรไฟล์')
@section('content')

    <section class="content">
        <div class="container-lg">
            <div class="mx-auto" style="max-width: 768px;">
                <form enctype="multipart/form-data">
                    <div class="row">
                        <!-- Section 1 -->
                        <div class="col">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label required">ชื่อผู้ใช้</label>
                                <div class="col-sm-9">
                                    <input type="text" name="user" class="form-control">
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
            // handleGetAllEmployee()
            // callSearchFunc = handleGetAllEmployee;
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
                    $('input[name="address"]').val();
                    $('input[name="phone"]').val(value.member_phone || value.employee_phone || value
                        .admin_phone);
                    $('input[name="facebook"]').val(value.member_facebook || value.employee_facebook || value
                        .admin_facebook);
                    $('input[name="lineid"]').val(value.member_lineid || value.employee_lineid || value
                        .admin_lineid);

                        setFilePreview(value.member_img)

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Failed');
                }
            }).always(async function() {
                await delay(1000)
                utils.setLinearLoading()
            });
        }
    </script>
@endsection
