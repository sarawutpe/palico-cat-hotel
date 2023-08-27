<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Employee;
use App\Models\Admin;
use App\Http\Helpers\Helper;
use App\Http\Helpers\Key;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use App\Rules\UniqueUser;
use App\Rules\UniqueEmail;

class UserController extends Controller
{
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
                $admin = Admin::findOrFail($id);
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
                'user' => 'nullable|string',
                'email' => 'nullable|string',
                'pass' => 'nullable|string',
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

            $file_formdata = "img";

            if ($type === Key::$member) {
                $member = Member::findOrFail($id);
                $request->validate([
                    'user' => ['required', new UniqueUser($member->member_user)],
                    'email' => ['required', new UniqueEmail($member->member_email)],
                ], [
                    'user.unique' => 'ชื่อผู้ใช้งานนี้มีอยู่แล้ว',
                    'email.unique' => 'อีเมลผู้ใช้งานนี้มีอยู่แล้ว',
                ]);

                $member->member_name = $request->input('name');
                $member->member_user = $request->input('user');
                $member->member_email = $request->input('email');

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
                    $member->member_img = Helper::uploadFile($request, $file_formdata);
                }

                // Remove file
                if ($request->input('set') === 'file_null') {
                    Helper::deleteFile($member->employee_img);
                    $member->employee_img = "";
                }

                Session::put('img', $member->member_img);

                $member->save();
                return response()->json(['success' => true, 'data' => 'อับเดตโปรไฟล์สำเร็จ']);
            }

            if ($type === Key::$employee) {
                $employee = Employee::findOrFail($id);
                $request->validate([
                    'user' => ['required', new UniqueUser($employee->employee_user)],
                    'email' => ['required', new UniqueEmail($employee->employee_email)],
                ], [
                    'user.unique' => 'ชื่อผู้ใช้งานนี้มีอยู่แล้ว',
                    'email.unique' => 'อีเมลผู้ใช้งานนี้มีอยู่แล้ว',
                ]);

                $employee->employee_name = $request->input('name');
                $employee->employee_user = $request->input('user');
                $employee->employee_email = $request->input('email');

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
                    $employee->employee_img = Helper::uploadFile($request, $file_formdata);
                }

                // Remove file
                if ($request->input('set') === 'file_null') {
                    Helper::deleteFile($employee->employee_img);
                    $employee->employee_img = "";
                }

                Session::put('img', $employee->employee_img);

                $employee->save();
                return response()->json(['success' => true, 'data' => 'อับเดตโปรไฟล์สำเร็จ']);
            }

            if ($type === Key::$admin) {
                $admin = Admin::findOrFail($id);
                $request->validate([
                    'user' => ['required', new UniqueUser($admin->admin_user)],
                    'email' => ['required', new UniqueEmail($admin->admin_email)],
                ], [
                    'user.unique' => 'ชื่อผู้ใช้งานนี้มีอยู่แล้ว',
                    'email.unique' => 'อีเมลผู้ใช้งานนี้มีอยู่แล้ว',
                ]);

                $admin->admin_name = $request->input('name');
                $admin->admin_user = $request->input('user');
                $admin->admin_email = $request->input('email');

                if ($request->filled('pass')) {
                    $admin->admin_pass = md5($request->input('pass'));
                }

                $admin->admin_address = $request->input('address');
                $admin->admin_phone = $request->input('phone');
                $admin->admin_facebook = $request->input('facebook');
                $admin->admin_lineid = $request->input('lineid');

                // Upload file
                if ($request->hasFile('img')) {
                    Helper::deleteFile($admin->admin_img);
                    $admin->admin_img = Helper::uploadFile($request, $file_formdata);
                }

                // Remove file
                if ($request->input('set') === 'file_null') {
                    Helper::deleteFile($admin->admin_img);
                    $admin->admin_img = "";
                }

                Session::put('img', $admin->admin_img);

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
