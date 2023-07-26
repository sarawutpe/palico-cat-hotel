@extends('layouts.home')

@section('content')
    <div class="container-lg">
        <div class="mx-auto" style="max-width: 576px;">
            <h2 class="text-center mb-4">ลืมรหัสผ่าน</h2>
            <form action="authen/forgot-password" method="POST">
                @csrf
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

                @if (session()->has('success'))
                    <div class="text-success font-medium mb-2">
                        {{ session('success') }}
                    </div>
                    <a href="/forgot-password" class="text-dark font-medium mb-2">
                        ส่งรหัสผ่านใหม่?
                    </a>
                @else
                    <div class="row">
                        <div class="col">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label required">ชื่อผู้ใช้</label>
                                <div class="col-sm-9">
                                    <input type="text" name="user" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label></label>
                                <div class="col-sm-9">
                                    <a href="login">
                                        <button type="button" class="btn btn-outline-dark">เข้าสู่ระบบ</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Button Group -->
                    <div class="d-flex justify-content-center gap-4">
                        <button type="submit" class="btn btn-primary">ถัดไป</button>
                    </div>
                @endif

            </form>
        </div>
    </div>
@endsection
