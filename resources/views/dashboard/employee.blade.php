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
                <form class="h-100" action="{{ route('employee.addEmployee') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col h-100">
                        <fieldset class="scroll">
                            <legend>จัดการข้อมูลพนักงาน</legend>

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
                                                    <img id="file-preview" src=""
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

                    </div>
                    <div class="d-flex gap-4" style="padding: 12px">
                        <button type="button" class="btn btn-secondary">พิมพ์รายงาน</button>
                        <button type="button" class="btn btn-info">แก้ไข</button>
                        <button type="reset" class="btn btn-danger">ลบ</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>

                    </div>
                </form>
            </div>

            <div class="col">
                <div class="">
                    <fieldset class="scroll">
                        <legend>รายชื่อพนักงาน</legend>
                        @if (count($employees) > 0)
                            @foreach ($employees as $employee)
                                <div class="box-card-list" onclick="handleClickEmployee('{{ json_encode($employee) }}')">
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

    </section>


    <script>
        var STORAGE_PATH = "{{ asset('storage/') }}"


        function handleClickEmployee(data = "") {
            const employee = JSON.parse(data)
            if (typeof employee === 'object') {
                var activeItem = (employee.employee_id || 0) - 1
                $('.box-card-list').removeClass('active').eq(activeItem).addClass('active');
                // $('.box-card-list').eq(employee.employee_id - 1 || 0).addClass('active');



                $('input[name="employee_id"]').val(employee.employee_id || "");
                $('input[name="employee_name"]').val(employee.employee_name || "");
                $('input[name="employee_user"]').val(employee.employee_user || "");
                $('input[name="employee_pass"]').val("");
                $('input[name="employee_address"]').val(employee.employee_address || "");
                $('input[name="employee_phone"]').val(employee.employee_phone || "");
                $('input[name="employee_facebook"]').val(employee.employee_facebook || "");
                $('input[name="employee_lineid"]').val(employee.employee_lineid || "");

                if (employee.employee_img) {
                    $('#file-preview').css('opacity', 1).attr('src', `${STORAGE_PATH}/${(employee.employee_img || "")}`);
                } else {
                    $('#file-preview').css('opacity', 0).attr('src', '');
                }

                console.log(employee)
            }
        }

        // Do you want to save the changes?
        // คุณต้องการลบ

        $(document).ready(function() {
            Swal.fire({
                title: '<strong>คุณต้องการลบข้อมูลหรือไม่?</strong>',
                icon: 'info',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
            })
        })
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