@php
    header('Cache-Control: no-store, private, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Pragma: no-cache');
@endphp

@extends('layouts.home')
@section('content')
    <div class="container-lg">
        <div class="mx-auto" style="max-width: 576px;">
            <h2 class="text-center mb-4">เข้าสู่ระบบ</h2>

            {{-- Debug: --}}
            <div class="d-flex justify-content-between p-4">
                <div class="avatar" onclick="example('MEMBER')">
                    MEMBER
                    <img class="avatar-img" src="https://coreui.io/docs/assets/img/avatars/2.jpg" alt="user@email.com">
                    <span class="avatar-status bg-success"></span>
                </div>
                <div class="avatar" onclick="example('EMPLOYEE')">
                    EMPLOYEE
                    <img class="avatar-img" src="https://coreui.io/docs/assets/img/avatars/2.jpg" alt="user@email.com">
                    <span class="avatar-status bg-success"></span>
                </div>
                <div class="avatar" onclick="example('ADMIN')">
                    ADMIN
                    <img class="avatar-img" src="https://coreui.io/docs/assets/img/avatars/2.jpg" alt="user@email.com">
                    <span class="avatar-status bg-success"></span>
                </div>
            </div>

            <form action="{{ route('authen.login') }}" method="POST">
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
                            <label></label>
                            <div class="col-sm-9">
                                <a href="recovery">
                                    <button type="button" class="btn btn-outline-dark">ลืมรหัสผ่าน</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Button Group -->
                <div class="d-flex justify-content-center gap-4">
                    <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function example(id) {
            if (id === 'MEMBER') {
                $('input[name="member_user"]').val('member');
                $('input[name="member_pass"]').val('1234');
            }
            if (id === 'EMPLOYEE') {
                $('input[name="member_user"]').val('employee');
                $('input[name="member_pass"]').val('1234');
            }
            if (id === 'ADMIN') {
                $('input[name="member_user"]').val('admin');
                $('input[name="member_pass"]').val('1234');
            }
            $('form').submit();
        }
    </script>

@endsection
