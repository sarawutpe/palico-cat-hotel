@extends('layouts.home')

@section('content')
    <div class="container-lg">
        <h2>ห้องพักและการจอง</h2>

        <p class="">จำนวน {{ count($rooms) }}</p>

        <div class="d-flex flex-wrap">
            @foreach ($rooms as $room)
                <div class="mx-4">
                    <h4>{{ $room->room_name }}</h4>
                    <p>{{ $room->room_type }}</p>
                    <p>{{ $room->room_price }}</p>
                    <p>{{ $room->room_detail }}</p>
                    <img src="storage/{{ $room->room_img }}" width="200px" height="200px">
                </div>
            @endforeach
        </div>

    </div>
@endsection
