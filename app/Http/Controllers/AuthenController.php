<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Employee;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Helper;
use App\Http\Helpers\Key;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

class AuthenController extends Controller
{
    public function register(Request $request)
    {
        $member_name = $request->input('member_name');
        $member_user = $request->input('member_user');
        $member_pass = $request->input('member_pass');
        $member_address = $request->input('member_address');
        $member_phone = $request->input('member_phone');
        $member_facebook = $request->input('member_facebook');
        $member_lineid = $request->input('member_lineid');

        // Validate the input
        $request->validate([
            'member_name' => 'required|string',
            'member_user' => 'required|unique:members',
            'member_pass' => 'required|string',
            'member_address' => 'required|string',
            'member_phone' => 'required|string',
            'member_facebook' => 'nullable|string',
            'member_lineid' => 'nullable|string',
            'member_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'member_name.required' => 'กรุณากรอกชื่อสมาชิก',
            'member_user.required' => 'กรุณากรอกชื่อผู้ใช้งานสมาชิก',
            'member_user.unique' => 'ชื่อผู้ใช้งานสมาชิกนี้มีอยู่แล้ว',
            'member_pass.required' => 'กรุณากรอกรหัสผ่านสมาชิก',
            'member_address.required' => 'กรุณากรอกที่อยู่สมาชิก',
            'member_phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์สมาชิก',
            'member_facebook.required' => 'กรุณากรอก Facebook สมาชิก',
            'member_img.image' => 'รูปภาพของสมาชิกต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
            'member_img.mimes' => 'รูปภาพของสมาชิกต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
            'member_img.max' => 'ขนาดรูปภาพของสมาชิกต้องไม่เกิน 2048 กิโลไบต์',
        ]);

        // Create a new Member instance and save it to the database
        $member = new Member([
            'member_name' => $member_name,
            'member_user' => $member_user,
            'member_pass' => md5($member_pass),
            'member_address' => $member_address,
            'member_phone' => $member_phone,
            'member_facebook' => $member_facebook,
            'member_lineid' => $member_lineid,
        ]);

        // Upload and save the member_img if provided
        if ($request->hasFile('member_img')) {
            $member->member_img = Helper::uploadFile($request, "member_img");
        }

        $member->save();

        return redirect()->route('register')->with('success', 'สมัครสมาชิกสำเร็จ');
    }

    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'member_user' => 'required|string',
            'member_pass' => 'required|string',
        ], [
            'member_user.required' => 'กรุณากรอกชื่อผู้ใช้งานสมาชิก',
            'member_pass.required' => 'กรุณากรอกรหัสผ่านสมาชิก',
        ]);

        $user = $request->input('member_user');
        $password = $request->input('member_pass');

        $member = Member::where('member_user', $user)
            ->where('member_pass', md5($password))
            ->first();

        $employee = Employee::where('emp_user', $user)
            ->where('emp_pass', md5($password))
            ->first();

        $admin = Admin::where('admin_user', $user)
            ->where('admin_pass', md5($password))
            ->first();

        $id = $member->member_id ?? $employee->emp_id ?? $admin->admin_id;
        $type = Key::$member;

        if ($employee) {
            $type = Key::$employee;
        } else if ($admin) {
            $type = Key::$admin;
        }

        if ($id && $type) {
            // Authentication succeeded
            Session::put('is_logged_in', true);
            Session::put('id', $id);
            Session::put('type', $type);
            return redirect()->route('dashboard');
        } else {
            // Authentication failed
            return redirect()->back()->with('error', 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง');
        }
    }

    public function logout()
    {
        Session::flash('is_logged_in');
        Session::flash('id');
        Session::flash('type');
        return redirect()->route('login');
    }

    public function forgotPassword(Request $request)
    {
        $user = $request->input('user');

        // Validate the input
        $request->validate([
            'user' => 'required|string',
        ], [
            'user.required' => 'กรุณากรอกชื่อสมาชิก',
        ]);

        $member = Member::where('member_user', $user)
            ->where('member_user', $user)
            ->first();

        $employee = Employee::where('emp_user', $user)
            ->where('emp_user', $user)
            ->first();

        $admin = Admin::where('admin_user', $user)
            ->where('admin_user', $user)
            ->first();

        $user = $member->member_user ?? $employee->emp_user ?? $admin->admin_user;
        $type = Key::$member;

        if ($employee) {
            $type = Key::$employee;
        } else if ($admin) {
            $type = Key::$admin;
        }

        if (!$user) {
            return redirect()->back()->with('error', 'ไม่พบผู้ใช้');
        }

        $secert = env('APP_SECRET', '6C4A9D33A78E12A2');
        $payload = json_encode(['user' => $user, 'type' => $type, 'timestamp' => now()->timestamp]);
        $encrpy_token = base64_encode(Crypt::encryptString($payload, $secert));

        return redirect()->back()->with('success', 'ตรวจสอบอีเมลเพื่อดำเนินการรีเซ็ตรหัสผ่าน' . $encrpy_token);
    }

    public function resetPassword($token)
    {

        // ZXlKcGRpSTZJbEJsV1ZwbFdUQlNla2xSVTA1VUx6WXlOMk5PY25jOVBTSXNJblpoYkhWbElqb2lWbE0xUlhKclExa3lhSE5NY2tOcUswa3JjVkJITjFaYVNtODROMnhyUkhkeVoyUlJjbTFhVGtjMGFEQkxOVWRFU21KelluSnlkVGNyTDFweGVVWnBNVVJhUTNaTE1WbHJURVo0YnpSUE1rbFFhblk0UkhjOVBTSXNJbTFoWXlJNkltSTBPR1ZpTkdGaVltUTNNekEyWVdObE56VTNNVEZtTmpBNU5HUmlZamRqTWpKbU9HSm1NREV3WkRjeU1HTmhORGt5T1RkbU5qSmtaREJsTXpNMFptVWlMQ0owWVdjaU9pSWlmUT09
        // dd($token);
        // ZXlKcGRpSTZJbU1yV0U1UFpERkhORmcxVkhaT01TdG1MemxPZG1jOVBTSXNJblpoYkhWbElqb2lXVU53TlRGQkwxWnhNVVpwVUdaa1MzTnhLMEprYjAxWVMyNXNkV3RyVTJsTVpIRmxPVU5zSzFRd2RqVXlNMnhJT0dWelFtNVdWbmM0Ym5Od2JqQXpSbEEwUzFSalpIVnVOa2RYWTB0a1FYbG5iVXBDUW5CclpuTlhSbFozV0RKb1YxUmxibXd6YUhneFkzcFBWbFpSVTJKMWNFdGFlRmhZTkc5c1RtZHpha05XWm0xdEwwbE1SbTFRWVUxTFoweGlhek5yZDFscmNuSnRiVnBDVkRjd2RGSnZkMGxuVXpCbU9IUTNSbEY2V2pjNU1TOWhVVFI1Y2pGd2NpODNabEU0YXpSQ2RYcERRV3RLVEN0WWRHSTNPRGxCVWt0c2VVcHlkMXB2VFRKSGNUUk1TRnBEWlZVMFEyRm1abnBZWVZrck5VUnpjMm8xYTFGRlJsQkZWMHB0ZURSWFVIZ3hVelZHYkRSblNrYzFNVTF6VUN0RFMzbGhiVmt5ZW1KQ1NrOVhibXhIWTFWMU5HMDFZMHBaYjJkNmFGVlRNRGN5Yms1VE1qQnNhRE54VVN0Q09IWXdNRWw1YVhBNVJrdHllRXhQVUhsR1UxZDRTWE5YU0hwMk15dGxhbVJLU1VONlFtMDVTbFl4TDB0RFFWTmxkMHBYYVhreFNEQmFaazUxV1ZBNVFXdDBVV3QxUkdOcGR5OVBhSGRTZDBscFVqSXhaREJLYVVOVk1UbGxSRFkyTURoa2FsaFlXV1ZJWmxOV1NIVmtNR1V4Y21sQ2ExSkZNbVZSWm1Sak1GVnNOVTlQTmtOTVkyWlVkamhLWW5NM1dsVnRVVk5sWlVSWGRVdzNTR0l5Um1VMWRFeHBhR2RwVHpBOUlpd2liV0ZqSWpvaVlXSmpZVFV3Wm1Ka1pXVm1OelpoWXpNNVpqVmtNV0V3TURNNU1tVmpOekF6TkRNMU16WTBaV0ZtT1RNd1lXWTVORFF3WVRjek4yVmtNakUyTW1SaU1DSXNJblJoWnlJNklpSjk
        $secert = env('APP_SECRET', '6C4A9D33A78E12A2');
        $token = base64_decode($token);
        $decode_token = Crypt::decryptString($token, $secert);
        $user_text = json_decode($decode_token, true);

        // $user = $user_text['user'] ?? "";
        // $type = $user_text['type'] ?? "";

        // dd([$user,$type]);
        // return redirect()->route('reset-password');


        // Via a request instance...
        // $request->session()->put('e', 'value');


        return view('reset-password')->with('error', 'โทรเค่นไม่ถูกต้อง...');

        // if (!$token) {
        // return redirect()->back()->with('error', 'เกิดข้อผิดพลาด');
        // }
    }
}
