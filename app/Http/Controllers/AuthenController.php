<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Employee;
use App\Models\Admin;
use App\Models\Token;

use App\Http\Helpers\Helper;
use App\Http\Helpers\Key;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

use App\Rules\UniqueUser;
use App\Rules\UniqueEmail;
use App\Http\Helpers\Mail;

class AuthenController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'member_name' => 'required|string',
                'member_user' => ['required', new UniqueUser],
                'member_pass' => 'required|string',
                'member_email' => ['required', new UniqueEmail],
                'member_address' => 'required|string',
                'member_phone' => 'required|string',
                'member_facebook' => 'nullable|string',
                'member_lineid' => 'nullable|string',
                'member_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'member_name.required' => 'กรุณากรอกชื่อ',
                'member_user.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
                'member_user.unique' => 'ชื่อผู้ใช้งานนี้มีอยู่แล้ว',
                'member_pass.required' => 'กรุณากรอกรหัสผ่าน',
                'member_user.required' => 'กรุณากรอกอีเมล',
                'member_email.required' => 'กรุณากรอกอีเมล',
                'member_email.unique' => 'อีเมลผู้ใช้งานนี้มีอยู่แล้ว',
                'member_address.required' => 'กรุณากรอกที่อยู่',
                'member_phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์',
                'member_facebook.required' => 'กรุณากรอก Facebook',
                'member_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'member_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'member_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            // Create a new Member instance and save it to the database
            $member = new Member([
                'member_name' => $request->input('member_name'),
                'member_user' => $request->input('member_user'),
                'member_pass' => md5($request->input('member_pass')),
                'member_email' => $request->input('member_email'),
                'member_address' => $request->input('member_address'),
                'member_phone' => $request->input('member_phone'),
                'member_facebook' => $request->input('member_facebook'),
                'member_lineid' => $request->input('member_lineid'),
            ]);

            // Upload and save the member_img if provided
            if ($request->hasFile('member_img')) {
                $member->member_img = Helper::uploadFile($request, "member_img");
            }

            $member->save();

            return redirect()->route('register')->with('success', 'สมัครสมาชิกสำเร็จ');
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
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

            $employee = Employee::where('employee_user', $user)
                ->where('employee_pass', md5($password))
                ->first();

            $admin = Admin::where('admin_user', $user)
                ->where('admin_pass', md5($password))
                ->first();

            $id = $member->member_id ?? $employee->employee_id ?? $admin->admin_id ?? null;
            $name = $member->member_name ?? $employee->employee_name ?? $admin->admin_name ?? null;
            $img = $member->member_img ?? $employee->employee_img ?? $admin->admin_img ?? null;

            $type = "";
            if ($member) {
                $type = Key::$member;
            } else if ($employee) {
                $type = Key::$employee;
            } else if ($admin) {
                $type = Key::$admin;
            }

            if ($id && $type && $name) {
                // Authentication succeeded
                Session::put('is_logged_in', true);
                Session::put('id', $id);
                Session::put('type', $type);
                Session::put('img', $img);
                return redirect()->route('dashboard');
            } else {
                // Authentication failed
                return redirect()->back()->with('error', 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง');
            }
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function logout()
    {
        try {
            // Clear specific session items
            Session::forget('is_logged_in');
            Session::forget('id');
            Session::forget('type');
            Session::forget('img');
            Session::flush();

            return redirect()->route('login');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function recovery(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'user' => 'required|string',
            ], [
                'user.required' => 'กรุณากรอกชื่อสมาชิก',
            ]);

            $user = $request->input('user');

            $member = Member::where('member_user', $user)
                ->where('member_user', $user)
                ->first();

            $employee = Employee::where('employee_user', $user)
                ->where('employee_user', $user)
                ->first();

            $admin = Admin::where('admin_user', $user)
                ->where('admin_user', $user)
                ->first();

            $id = $member->member_id ?? $employee->employee_id ?? $admin->admin_id ?? null;
            $user = $member->member_user ?? $employee->employee_user ?? $admin->admin_user ?? null;
            $email_to = $member->member_email ?? $employee->employee_email ?? $admin->admin_email ?? null;

            if (!$email_to) {
                return redirect()->back()->with('error', 'ไม่พบอีเมลผู้ใช้');
            }

            $type = "";
            if ($member) {
                $type = Key::$member;
            } else if ($employee) {
                $type = Key::$employee;
            } else if ($admin) {
                $type = Key::$admin;
            } else {
                return redirect()->back()->with('error', 'ไม่พบผู้ใช้');
            }

            $secert = env('APP_SECRET', '6C4A9D33A78E12A2');
            $payload = json_encode(['id' => $id, 'user' => $user, 'type' => $type, 'timestamp' => now()->timestamp]);
            $encrpy_token = Crypt::encryptString($payload, $secert);

            // Save token to db
            $token = new Token(['token' => sha1($encrpy_token)]);
            $token->save();

            // Send to mail
            $url_to_recovery = url('/recovery/reset/' . $encrpy_token);
            Mail::sendMail('', $email_to, $url_to_recovery);

            return redirect()->back()->with('success', 'ตรวจสอบอีเมลเพื่อดำเนินการรีเซ็ตรหัสผ่าน');
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function recoveryReset(Request $request, $token)
    {
        try {
            $request->validate([
                'password' => 'required|string',
            ], [
                'password.required' => 'กรุณากรอกรหัสผ่านสมาชิก',
            ]);

            // Check if invalid token
            $token_model = Token::where('token', sha1($token))->first();
            if (!$token_model || $token_model->is_expired) {
                return redirect()->back()->with('error', 'Token หมดอายุ');
            }

            // Set token is_expired to db
            $token_model->is_expired = 1;
            $token_model->save();

            $secert = env('APP_SECRET', '6C4A9D33A78E12A2');
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
            } else if ($type === Key::$employee) {
                $employee = Employee::find($id);
                $employee->employee_pass = md5($password);
                $employee->save();
            } else if ($type === Key::$admin) {
                $admin = Admin::find($id);
                $admin->admin_pass = md5($password);
                $admin->save();
            }

            return redirect()->route('login')->with('success', 'เปลี่ยนรหัสผ่านสำเร็จ');
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
