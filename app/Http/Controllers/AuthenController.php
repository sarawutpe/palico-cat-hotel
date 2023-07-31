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
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use App\Rules\UniqueUser;
use Illuminate\Support\Facades\DB;

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
                'member_user' => ['required', new UniqueUser],
                'member_pass' => 'required|string',
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
                'member_address.required' => 'กรุณากรอกที่อยู่',
                'member_phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์',
                'member_facebook.required' => 'กรุณากรอก Facebook',
                'member_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'member_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'member_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
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
                Session::put('name', $name);
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
            Session::forget('name');
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
            $token = Token::where('token', sha1($token))->first();
            if (empty($token) || $token->is_expired) {
                return redirect()->back()->with('error', 'Token หมดอายุ');
            }

            // Set token is_expired to db
            $token->is_expired = 1;
            $token->save();

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

    public function getProfile($type, $id)
    {
        try {
            if ($type === Key::$member) {
                $member = Member::findOrFail($id);
                return response()->json(['success' => true, 'data' => $member]);
            }

            if ($type === Key::$employee) {
                $employee = Employee::findOrFail($id);
                return response()->json(['success' => true, 'data' => $employee]);
            }

            if ($type === Key::$admin) {
                $admin = Member::findOrFail($id);
                return response()->json(['success' => true, 'data' => $admin]);
            }

            return response()->json(['success' => false, 'message' => "ประเภทบัญชีไม่ถูกต้อง"], 404);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request, $type, $id)
    {
        try {
            // Validate the input
            $request->validate([
                'name' => 'required|string',
                'user' => ['required', new UniqueUser],
                'pass' => 'required|string',
                'address' => 'required|string',
                'phone' => 'required|string',
                'facebook' => 'nullable|string',
                'lineid' => 'nullable|string',
                'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'name.required' => 'กรุณากรอกชื่อ',
                'user.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
                'user.unique' => 'ชื่อผู้ใช้งานนี้มีอยู่แล้ว',
                'pass.required' => 'กรุณากรอกรหัสผ่าน',
                'address.required' => 'กรุณากรอกที่อยู่',
                'phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์',
                'facebook.required' => 'กรุณากรอก Facebook',
                'img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            if ($type === Key::$member) {
                $member = Member::findOrFail($id);
                $member->member_name = $request->input('name');
                $member->member_user = $request->input('user');

                if ($request->filled('pass')) {
                    $member->member_pass = md5($request->input('pass'));
                }

                $member->member_address = $request->input('address');
                $member->member_phone = $request->input('phone');
                $member->member_facebook = $request->input('facebook');
                $member->member_lineid = $request->input('lineid');

                // Upload file
                if ($request->hasFile('img')) {
                    Helper::deleteFile($member->member_img);
                    $member->member_img = Helper::uploadFile($request, "member_img");
                }

                // Remove file
                if ($request->input('set') === 'file_null') {
                    Helper::deleteFile($member->employee_img);
                    $member->employee_img = "";
                }

                $member->save();
                return response()->json(['success' => true, 'data' => 'อับเดตโปรไฟล์สำเร็จ']);
            }

            if ($type === Key::$employee) {
                $employee = Employee::findOrFail($id);
                $employee->employee_name = $request->input('name');
                $employee->employee_user = $request->input('user');

                if ($request->filled('pass')) {
                    $employee->employee_pass = md5($request->input('pass'));
                }

                $employee->employee_address = $request->input('address');
                $employee->employee_phone = $request->input('phone');
                $employee->employee_facebook = $request->input('facebook');
                $employee->employee_lineid = $request->input('lineid');

                // Upload file
                if ($request->hasFile('img')) {
                    Helper::deleteFile($employee->employee_img);
                    $employee->employeer_img = Helper::uploadFile($request, "employee_img");
                }

                // Remove file
                if ($request->input('set') === 'file_null') {
                    Helper::deleteFile($employee->employee_img);
                    $employee->employee_img = "";
                }

                $employee->save();
                return response()->json(['success' => true, 'data' => 'อับเดตโปรไฟล์สำเร็จ']);
            }

            if ($type === Key::$admin) {
                $admin = Member::findOrFail($id);
                $admin->admin_name = $request->input('name');
                $admin->admin_user = $request->input('user');

                if ($request->filled('pass')) {
                    $admin->admin_pass = md5($request->input('pass'));
                }

                $admin->admin_address = $request->input('address');
                $admin->employee_phone = $request->input('phone');
                $admin->admin_facebook = $request->input('facebook');
                $admin->admin_lineid = $request->input('lineid');

                // Upload file
                if ($request->hasFile('img')) {
                    Helper::deleteFile($admin->admin_img);
                    $admin->admin_img = Helper::uploadFile($request, "admin_img");
                }

                // Remove file
                if ($request->input('set') === 'file_null') {
                    Helper::deleteFile($admin->admin_img);
                    $admin->admin_img = "";
                }

                $admin->save();
                return response()->json(['success' => true, 'data' => 'อับเดตโปรไฟล์สำเร็จ']);
            }

            return response()->json(['success' => false, 'message' => "ประเภทบัญชีไม่ถูกต้อง"], 404);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
