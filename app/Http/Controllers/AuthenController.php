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
use PhpParser\Node\Stmt\TryCatch;

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

    public function recovery(Request $request)
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

        $id = $member->member_id ?? $employee->emp_id ?? $admin->admin_id;
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
        $payload = json_encode(['id' => $id, 'user' => $user, 'type' => $type, 'timestamp' => now()->timestamp]);

        $encrpy_token = base64_encode(Crypt::encryptString($payload, $secert));
        return redirect()->back()->with('success', 'ตรวจสอบอีเมลเพื่อดำเนินการรีเซ็ตรหัสผ่าน ' . $encrpy_token);;
    }

    public function recoveryReset(Request $request, $token)
    {
        try {
            $secert = env('APP_SECRET', '6C4A9D33A78E12A2');
            $token = base64_decode($token);
            $decrypt_token = Crypt::decryptString($token, $secert);
            $user_text = json_decode($decrypt_token, true);

            $id = $user_text['id'];
            $user = $user_text['user'];
            $type = $user_text['type'];
            $password = $request->input('password');

            if (empty($user) || empty($type)) {
                return redirect()->back()->with('error', 'เกิดข้อผิดพลาด');
            }

            if ($type === Key::$member) {
                $member = Member::find($id);
                $member->member_pass = md5($password);
                $member->save();
            }

            if ($type === Key::$employee) {
                $employee = Employee::find($id);
                $employee->emp_pass = md5($password);
                $employee->save();
            }

            if ($type === Key::$admin) {
                $admin = Admin::find($id);
                $admin->admin_pass = md5($password);
                $admin->save();
            }

            return redirect()->route('login')->with('success', 'เปลี่ยนรหัสผ่านสำเร็จ');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
