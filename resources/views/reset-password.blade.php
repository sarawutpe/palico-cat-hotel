@extends('layouts.home')

@section('content')
    <div class="container-lg">
        <div class="mx-auto" style="max-width: 576px;">
            <h2 class="text-center mb-4">รีเซ็ตรหัสผ่าน</h2>
            <form action="authen/forgot-password" method="POST">
                @csrf

                @if (session('error'))
                    <div class="text-danger font-medium mb-2">
                        {{ session('error') }}
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

                @if (session()->has('success'))
                    <div class="text-success font-medium mb-2">
                        {{ session('success') }}
                    </div>
                @else
                    <div class="row">
                        <div class="col">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label required">รหัสผ่าน</label>
                                <div class="col-sm-9">
                                    <input type="password" name="pass" class="form-control">
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
