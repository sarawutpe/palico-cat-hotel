@extends('layouts.home')

@section('content')
    <div class="container-lg">
        <h2>ห้องพักและการจอง</h2>

        <p>จำนวน {{ count($rooms) }}</p>

        <div class="d-flex flex-wrap gap-4">
            @foreach ($rooms as $room)
                <div class="card" style="width: 18rem;">
                    <div>
                        <img src="storage/{{ $room->room_img }}" class="card-img-top" width="100%" height="180px">
                    </div>
                    <div class="card-body h-100 d-flex flex-column" style="flex: inherit;">
                        <div class="mb-2 d-flex flex-column flex-1" style="flex: 1">
                            <h5 class="card-title">{{ $room->room_name }}</h5>
                            <h5 class="card-title">{{ $room->room_type }}</h5>
                            <p class="card-text">฿ {{ $room->room_price }}</p>
                            <p>{{ $room->room_detail }}</p>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">จอง</a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
