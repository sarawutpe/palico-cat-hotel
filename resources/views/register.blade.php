@extends('layouts.home')

@section('content')
    <div class="container-lg">
        <div class="mx-auto" style="max-width: 768px;">
            <h2 class="text-center mb-4">สมัครสมาชิก</h2>
            <form action="{{ route('authen.register') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if (session()->has('success'))
                    <div class="text-success font-medium mb-2">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="text-danger font-medium mb-2">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="text-danger font-medium mb-2">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <!-- Section 1 -->
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label required">ชื่อผู้ใช้</label>
                            <div class="col-sm-9">
                                <input type="text" name="member_user" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label required">รหัสผ่าน</label>
                            <div class="col-sm-9">
                                <input type="password" name="member_pass" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label required">อีเมล</label>
                            <div class="col-sm-9">
                                <input type="text" name="member_email" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label required">ชื่อ-สกุล</label>
                            <div class="col-sm-9">
                                <input type="text" name="member_name" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label required">ที่อยู่</label>
                            <div class="col-sm-9">
                                <input type="text" name="member_address" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label required">เบอร์โทรศัพท์</label>
                            <div class="col-sm-9">
                                <input type="text" name="member_phone" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Facebook</label>
                            <div class="col-sm-9">
                                <input type="text" name="member_facebook" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Line ID</label>
                            <div class="col-sm-9">
                                <input type="text" name="member_lineid" class="form-control">
                            </div>
                        </div>
                    </div>
                    <!-- Section 2 Upload -->
                    <div class="col">
                        <div class="mb-3">
                            <div class="d-flex flex-column gap-2" style="max-width: fit-content;">
                                <div class="border rounded bg-white" style="overflow: hidden; width: 250px; height: 250px">
                                    <img id="file-preview" src="" style="object-fit: cover; opacity: 0;"
                                        width="100%" height="100%">
                                </div>
                                <div class="d-flex gap-2">
                                    <div class="btn btn-light position-relative w-100">
                                        <input type="file" id="file-upload" name="member_img"
                                            accept="image/png, image/jpeg" class="position-absolute opacity-0 w-100 h-100"
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
                    <a href="/">
                        <button type="button" class="btn btn-outline-danger">ยกเลิก</button>
                    </a>
                    <button type="reset" class="btn btn-outline-dark">แก้ไข</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // File Change
        let filePreviewElement = $('#file-preview')
        let fileUploadElement = $('#file-upload')
        let deleteFileElement = $('#file-delete')

        fileUploadElement.change(function(event) {
            const fileInput = event.target;

            if (fileInput && fileInput.files) {
                const file = fileInput.files[0];
                const imageURL = URL.createObjectURL(file);

                filePreviewElement.css("opacity", 1).attr("src", imageURL);
                deleteFileElement.parent().show()
            } else {
                filePreviewElement.css("opacity", 0).attr("src", "");
                deleteFileElement.parent().hide();
            }
        });

        deleteFileElement.click(function(event) {
            $(this).parent().hide();
            filePreviewElement.css("opacity", 0).attr("src", "");
            fileUploadElement.val('')
        });
    </script>
@endsection
