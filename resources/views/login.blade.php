@extends('layouts.home')

@section('content')
    <div class="container-lg">
        <div class="mx-auto" style="max-width: 576px;">
            <h2 class="text-center mb-4">เข้าสู่ระบบ</h2>
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
                                <a href="forgot">
                                    <input type="password" name="member_pass" class="form-control">
                                </a>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label></label>
                            <div class="col-sm-9">
                                <a href="forgot-password">
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
@endsection
