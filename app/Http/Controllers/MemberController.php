<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Helpers\Helper;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Rules\UniqueUser;
use App\Rules\UniqueEmail;

class MemberController extends Controller
{
    public function getAllMember(Request $request)
    {
        try {
            $q = $request->input('q', '');
            $query = Member::orderBy('updated_at', 'desc');
            $query->where('member_name', 'like', '%' . $q . '%');
            $members = $query->get();
            return response()->json(['success' => true, 'data' => $members]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function addMember(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'member_name' => 'required|string',
                'member_user' => ['required', new UniqueUser()],
                'member_pass' => 'required|string',
                'member_email' => ['required', new UniqueEmail()],
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
                'member_email.required' => 'กรุณากรอกอีเมล',
                'member_email.unique' => 'อีเมลผู้ใช้งานนี้มีอยู่แล้ว',
                'member_address.required' => 'กรุณากรอกที่อยู่',
                'member_phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์',
                'member_facebook.required' => 'กรุณากรอก Facebook ',
                'member_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'member_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'member_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            $member = new Member();
            $member->member_name = $request->input('member_name');
            $member->member_user = $request->input('member_user');
            $member->member_pass = md5($request->input('member_pass'));
            $member->member_email = $request->input('member_email');
            $member->member_address = $request->input('member_address');
            $member->member_phone = $request->input('member_phone');
            $member->member_facebook = $request->input('member_facebook');
            $member->member_lineid = $request->input('member_lineid');

            // Upload and save the member_img if provided
            if ($request->hasFile('member_img')) {
                $member->member_img = Helper::uploadFile($request, "member_img");
            }

            $member->save();
            return response()->json(['success' => true, 'data' => $member]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateMember(Request $request, $id)
    {
        try {
            $member = Member::findOrFail($id);
            // Validate the input
            $request->validate([
                'member_name' => 'required|string',
                'member_user' => ['required', new UniqueUser($member->member_user)],
                'member_pass' => 'nullable|string',
                'member_email' => ['required', new UniqueEmail($member->member_email)],
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
                'member_email.required' => 'กรุณากรอกอีเมล',
                'member_email.unique' => 'อีเมลผู้ใช้งานนี้มีอยู่แล้ว',
                'member_address.required' => 'กรุณากรอกที่อยู่',
                'member_phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์',
                'member_facebook.required' => 'กรุณากรอก Facebook ',
                'member_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'member_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'member_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            $member->member_name = $request->input('member_name');
            $member->member_user = $request->input('member_user');
            $member->member_email = $request->input('member_email');

            if ($request->filled('member_pass')) {
                $member->member_pass = md5($request->input('member_pass'));
            }

            $member->member_address = $request->input('member_address');
            $member->member_phone = $request->input('member_phone');
            $member->member_facebook = $request->input('member_facebook');
            $member->member_lineid = $request->input('member_lineid');

            // Upload and save the member_img if provided
            if ($request->hasFile('member_img')) {
                Helper::deleteFile($member->member_img);
                $member->member_img = Helper::uploadFile($request, "member_img");
            }

            // Remove file
            if ($request->input('set') === 'file_null') {
                Helper::deleteFile($member->member_img);
                $member->member_img = "";
            }

            $member->save();
            return response()->json(['success' => true, 'data' => $member]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function deleteMember($id)
    {
        try {
            $member = Member::findOrFail($id);
            $member->delete();
            return response()->json(['success' => true], 200);
        } catch (ValidationException $exception) {
            return response()->json(['success' => false, 'errors' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
