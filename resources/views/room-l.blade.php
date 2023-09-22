@extends('layouts.home')

@section('content')
    <div class="container-lg">
        <h2>ห้องพักขนาด l</h2>

        <div class="d-flex flex-wrap gap-4">
            <img src="{{ asset('assets/img/l1.jpg') }}" width="500px" alt="">
            <img src="{{ asset('assets/img/l2.jpg') }}" width="500px" alt="">
            <img src="{{ asset('assets/img/l3.jpg') }}" width="500px" alt="">
        </div>
    </div>
@endsection
