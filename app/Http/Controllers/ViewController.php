<?php

namespace App\Http\Controllers;

use App\Models\Room;
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
        return view('room');
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

    public function guide()
    {
        return view('guide');
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
        if (Helper::is_member()) {
            return view('dashboard.member-index');
        }

        if (Helper::is_employee()) {
            return view('dashboard.employee-index');
        }

        if (Helper::is_admin()) {
            return view('dashboard.admin-index');
        }
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

    public function dashboardProduct()
    {
        return view('dashboard.product');
    }

    public function dashboardReport()
    {
        return view('dashboard.report');
    }

    public function dashboardProfile()
    {
        return view('dashboard.profile');
    }
}
