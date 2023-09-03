@php
    $session_type = session('type', '');
    $route_name = Route::currentRouteName();
@endphp

<ul class="sidebar-nav">
    @if ($session_type === 'MEMBER')
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.cat' ? ' active' : '' }}"
                href="{{ route('dashboard.cat') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>แมว
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.book' ? ' active' : '' }}"
                href="{{ route('dashboard.book') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>จอง
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.book.history' ? ' active' : '' }}"
                href="{{ route('dashboard.book.history') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>ประวัติการจอง
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.book.service' ? ' active' : '' }}"
                href="{{ route('dashboard.book.service') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>การดูแลแมว
            </a>
        </li>
    @endif

    @if ($session_type === 'EMPLOYEE')
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.member' ? ' active' : '' }}"
                href="{{ route('dashboard.member') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>ลูกค้า
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.cat' ? ' active' : '' }}"
                href="{{ route('dashboard.cat') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>แมว
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.book.manage' ? ' active' : '' }}"
                href="{{ route('dashboard.book.manage') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>การจอง
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.book.history' ? ' active' : '' }}"
                href="{{ route('dashboard.book.history') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>ประวัติการจอง
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.book.service' ? ' active' : '' }}"
                href="{{ route('dashboard.book.service') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>การดูแลแมว
            </a>
        </li>
    @endif

    @if ($session_type === 'ADMIN')
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard' ? ' active' : '' }}" href="{{ route('dashboard') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg>หน้าแรก
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.employee' ? ' active' : '' }}"
                href="{{ route('dashboard.employee') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>พนักงาน
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.room' ? ' active' : '' }}"
                href="{{ route('dashboard.room') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>ห้องพัก
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.cat' ? ' active' : '' }}"
                href="{{ route('dashboard.cat') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>แมว
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.book.manage' ? ' active' : '' }}"
                href="{{ route('dashboard.book.manage') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>การจอง
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.book.history' ? ' active' : '' }}"
                href="{{ route('dashboard.book.history') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>ประวัติการจอง
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $route_name === 'dashboard.book.service' ? ' active' : '' }}"
                href="{{ route('dashboard.book.service') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use>
                </svg>การดูแลแมว
            </a>
        </li>
    @endif
</ul>

<button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
