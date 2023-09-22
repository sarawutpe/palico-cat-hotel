@extends('layouts.home')

@section('content')
    <div class="container-lg">
        <h2>ห้องพักขนาด S</h2>

        <div class="d-flex flex-wrap gap-4">
            <img src="{{ asset('assets/img/s1.jpg') }}" width="500px" alt="">
            <img src="{{ asset('assets/img/s2.jpg') }}" width="500px" alt="">
        </div>
    </div>
@endsection
