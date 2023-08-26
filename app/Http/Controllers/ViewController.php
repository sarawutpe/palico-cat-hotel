<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\Helper;

class ViewController extends Controller
{
    // Home Group
    public function home()
    {
        return view('index');
    }

    public function room()
    {
        $rooms = Room::orderBy('updated_at', 'desc')->get();
        return view('room', ['rooms' => $rooms]);
    }

    public function roomS()
    {
        return view('room-s');
    }

    public function roomM()
    {
        return view('room-m');
    }

    public function roomL()
    {
        return view('room-l');
    }

    public function service()
    {
        return view('service');
    }

    public function price()
    {
        return view('price');
    }

    public function rule()
    {
        return view('rule');
    }

    public function contact()
    {
        return view('contact');
    }

    // Authen Group
    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function recovery()
    {

        return view('recovery');
    }

    public function recoveryReset()
    {
        return view('recovery-reset');
    }

    // Dashboard
    public function dashboard()
    {
        return view('dashboard.index');
    }

    public function dashboardEmployee()
    {
        return view('dashboard.employee');
    }

    public function dashboardMember()
    {
        return view('dashboard.member');
    }

    public function dashboardCat()
    {
        return view('dashboard.cat');
    }

    public function dashboardRoom()
    {
        return view('dashboard.room');
    }

    public function dashboardBook()
    {
        return view('dashboard.book');
    }

    public function dashboardBookManage()
    {
        return view('dashboard.book-manage');
    }

    public function dashboardService()
    {
        if (Helper::is_member() || Helper::is_admin()) {
            return view('dashboard.view-service');
        }
        return view('dashboard.manage-service');
    }

    public function dashboardBookHistory()
    {
        if (Helper::is_member()) {
            return view('dashboard.member-book-history');
        }

        return view('dashboard.book-history');
    }

    public function dashboardProfile()
    {
        return view('dashboard.profile');
    }
}
