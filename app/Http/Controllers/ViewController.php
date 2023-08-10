<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class ViewController extends Controller
{
    public function homePage()
    {
        return view('index');
    }

    public function loginPage()
    {
        return view('login');
    }

    public function registerPage()
    {
        return view('register');
    }

    public function recoveryPage()
    {

        return view('recovery');
    }

    public function recoveryResetPage()
    {
        return view('recovery-reset');
    }

    public function roomPage()
    {
        $rooms = Room::orderBy('updated_at', 'desc')->get();
        return view('room', ['rooms' => $rooms]);
    }

    public function pricePage()
    {
        return view('price');
    }

    public function rulePage()
    {
        return view('rule');
    }

    public function contactPage()
    {
        return view('contact');
    }
}
