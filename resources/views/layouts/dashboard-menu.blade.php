<ul class="sidebar-nav">
    @if (session('type') === 'MEMBER')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg>หน้าแรก
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.cat') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>แมว
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.book') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>จอง
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.book.history') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>ประวัติการจอง
            </a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.book.history') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>ประวัติการจอง
            </a>
        </li> --}}
    @endif

    @if (session('type') === 'EMPLOYEE')
    @endif

    @if (session('type') === 'ADMIN')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg>หน้าแรก
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.employee') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>พนักงาน
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.room') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>ห้องพัก
            </a>
        </li>
    @endif
</ul>

<button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>

