<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Employee;
use App\Models\Admin;
use App\Models\Token;

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
        try {
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
            $member_model = new Member([
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
                $member_model->member_img = Helper::uploadFile($request, "member_img");
            }

            $member_model->save();

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

            $employee = Employee::where('emp_user', $user)
                ->where('emp_pass', md5($password))
                ->first();

            $admin = Admin::where('admin_user', $user)
                ->where('admin_pass', md5($password))
                ->first();

            $id = $member->member_id ?? $employee->emp_id ?? $admin->admin_id;
            $type = "";

            if ($member) {
                $type = Key::$member;
            } else if ($employee) {
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
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function logout()
    {
        try {
            Session::flash('is_logged_in');
            Session::flash('id');
            Session::flash('type');
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

            $member_model = Member::where('member_user', $user)
                ->where('member_user', $user)
                ->first();

            $employee_model = Employee::where('emp_user', $user)
                ->where('emp_user', $user)
                ->first();

            $admin_model = Admin::where('admin_user', $user)
                ->where('admin_user', $user)
                ->first();

            $id = $member_model->member_id ?? $employee_model->emp_id ?? $admin_model->admin_id ?? null;
            $user = $member_model->member_user ?? $employee_model->emp_user ?? $admin_model->admin_user ?? null;
            $type = "";

            if ($member_model) {
                $type = Key::$member;
            } else if ($employee_model) {
                $type = Key::$employee;
            } else if ($admin_model) {
                $type = Key::$admin;
            } else {
                return redirect()->back()->with('error', 'ไม่พบผู้ใช้');
            }

            $secert = env('APP_SECRET', '6C4A9D33A78E12A2');
            $payload = json_encode(['id' => $id, 'user' => $user, 'type' => $type, 'timestamp' => now()->timestamp]);
            $encrpy_token = Crypt::encryptString($payload, $secert);

            // Save token to db
            $token_model = new Token(['token' => sha1($encrpy_token)]);
            $token_model->save();

            return redirect()->back()->with('success', 'ตรวจสอบอีเมลเพื่อดำเนินการรีเซ็ตรหัสผ่าน ' . $encrpy_token);
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
            if (empty($token_model) || $token_model->is_expired) {
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
                $member_model = Member::find($id);
                $member_model->member_pass = md5($password);
                $member_model->save();
            } else if ($type === Key::$employee) {
                $employee_model = Employee::find($id);
                $employee_model->emp_pass = md5($password);
                $employee_model->save();
            } else if ($type === Key::$admin) {
                $admin_model = Admin::find($id);
                $admin_model->admin_pass = md5($password);
                $admin_model->save();
            }

            return redirect()->route('login')->with('success', 'เปลี่ยนรหัสผ่านสำเร็จ');
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
