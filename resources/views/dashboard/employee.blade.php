@extends('layouts.dashboard')
@section('title', 'จัดการข้อมูลพนักงาน')
@section('content')
    <style>
        .box-card-list {
            border: 1px solid transparent;
            border-radius: 6px;
            cursor: pointer;
        }

        .box-card-list:hover {
            background: rgba(255, 255, 255, 0.50);
            border-color: #eee;
        }
    </style>
    <div class="px-5">
        <div class="row">
            <div class="col">
                <form action="{{ route('employee.addEmployee') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="">
                        <fieldset>
                            <div class="row">
                                <legend>จัดการข้อมูลพนักงาน</legend>
                                <!-- Section 1 -->
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
                                                <img id="file-preview" src="" style="object-fit: cover; opacity: 0;"
                                                    width="100%" height="100%">
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
                                <!-- Image -->
                                <div class="text-center">
                                    <img src="" class="rounded">
                                </div>
                            </div>
                        </fieldset>
                        <div class="d-flex gap-4" style="padding: 12px">
                            <button type="button" class="btn btn-secondary">พิมพ์รายงาน</button>
                            <button type="button" class="btn btn-info">แก้ไข</button>
                            <button type="reset" class="btn btn-danger">ลบ</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- box-card-list d-flex justify-content-between p-2 gap-2 border-bottom --}}
            <div class="col">
                <div class="" style="overflow-y: scroll; max-height: 100vh">
                    <fieldset>
                        <legend>รายชื่อพนักงาน</legend>
                        @if (count($employees) > 0)
                            @foreach ($employees as $employee)
                                <div onclick="handleClickEmployee('{{ json_encode($employee) }}')" class="box-card-list">
                                    <div>
                                        <p>รหัสพนักงาน {{ $employee->employee_id }}</p>
                                        <p>ชื่อ-สกุล {{ $employee->employee_name }}</p>
                                        <p>เบอร์โทรศัพท์ {{ $employee->employee_phone }}</p>
                                    </div>
                                    <div class="border rounded bg-white"
                                        style="overflow: hidden; width: 100px; height: 100px">
                                        <img id="file-preview" onerror="this.style.opacity = 0"
                                            src="{{ asset('storage/' . $employee->employee_img) }}"
                                            style="object-fit: cover;" width="100%" height="100%">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No employees found.</p>
                        @endif
                    </fieldset>

                </div>
            </div>
        </div>
    </div>

    <script>
        // $(function() {
            function handleClickEmployee(data) {
                console.log('start')
                console.log(JSON.parse(data))
            }
        // })
    </script>

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
