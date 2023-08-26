@extends('layouts.home')

@section('content')
    <style>
        .zoom {
            transition: transform 150ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .zoom:hover {
            transform: scale(1.1)
        }
    </style>

    <div class="container-lg">
        <h2>ห้องพักและการจอง</h2>

        @php
            $room_types = collect([['id' => 1, 'name' => 'ห้องเล็ก', 'room_type' => 'S', 'img' => 'assets/img/hotel-1.jpg', 'route' => 'room-s'], ['id' => 2, 'name' => 'ห้องกลาง', 'room_type' => 'M', 'img' => 'assets/img/hotel-2.jpg', 'route' => 'room-m'], ['id' => 3, 'name' => 'ห้องใหญ่', 'room_type' => 'L', 'img' => 'assets/img/hotel-3.jpg', 'route' => 'room-l']])->map(function ($item) {
                return (object) $item;
            });
        @endphp

        <div class="d-flex flex-wrap gap-4">
            @foreach ($room_types as $room_type)
                <a href="{{ route($room_type->route) }}" class="zoom">
                    <div class="card" style="width: 18rem;">
                        <div>
                            <img src="{{ $room_type->img }}" class="card-img-top" width="100%" height="180px">
                        </div>
                        <div class="card-body h-100 d-flex flex-column" style="flex: inherit;">
                            <div class="mb-2 d-flex flex-column flex-1" style="flex: 1">
                                <h5 class="card-title">{{ $room_type->name }}</h5>
                                <h5 class="card-title">{{ $room_type->room_type }}</h5>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
@endsection
