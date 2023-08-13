<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

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

    public function dashboardBookHistory()
    {
        return view('dashboard.book-history');
    }

    public function dashboardProfile()
    {
        return view('dashboard.profile');
    }
}
