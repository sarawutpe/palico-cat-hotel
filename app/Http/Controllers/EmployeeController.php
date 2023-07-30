<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Helpers\Helper;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Rules\UniqueUser;

// add get update delete
class EmployeeController extends Controller
{
    public function getAllEmployee()
    {
        try {
            $employees = Employee::orderBy('updated_at', 'desc')->get();
            return response()->json(['success' => true, 'data' => $employees]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function addEmployee(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'employee_name' => 'required|string',
                'employee_user' => ['required', new UniqueUser()],
                'employee_pass' => 'required|string',
                'employee_address' => 'required|string',
                'employee_phone' => 'required|string',
                'employee_facebook' => 'nullable|string',
                'employee_lineid' => 'nullable|string',
                'employee_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'employee_name.required' => 'กรุณากรอกชื่อ',
                'employee_user.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
                'employee_user.unique' => 'ชื่อผู้ใช้งานนี้มีอยู่แล้ว',
                'employee_pass.required' => 'กรุณากรอกรหัสผ่าน',
                'employee_address.required' => 'กรุณากรอกที่อยู่',
                'employee_phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์',
                'employee_facebook.required' => 'กรุณากรอก Facebook ',
                'employee_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'employee_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'employee_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            $employee_name = $request->input('employee_name');
            $employee_user = $request->input('employee_user');
            $employee_pass = $request->input('employee_pass');
            $employee_address = $request->input('employee_address');
            $employee_phone = $request->input('employee_phone');
            $employee_facebook = $request->input('employee_facebook');
            $employee_lineid = $request->input('employee_lineid');

            $employee = new Employee([
                'employee_name' => $employee_name,
                'employee_user' => $employee_user,
                'employee_pass' => md5($employee_pass),
                'employee_address' => $employee_address,
                'employee_phone' => $employee_phone,
                'employee_facebook' => $employee_facebook,
                'employee_lineid' => $employee_lineid,
            ]);

            // Upload and save the employee_img if provided
            if ($request->hasFile('employee_img')) {
                $employee->employee_img = Helper::uploadFile($request, "employee_img");
            }

            $employee->save();
            return response()->json(['success' => true, 'data' => $employee]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateEmployee(Request $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            // Validate the input
            $request->validate([
                'employee_name' => 'required|string',
                'employee_user' => ['required', new UniqueUser($employee->employee_user)],
                'employee_pass' => 'nullable|string',
                'employee_address' => 'required|string',
                'employee_phone' => 'required|string',
                'employee_facebook' => 'nullable|string',
                'employee_lineid' => 'nullable|string',
                'employee_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'employee_name.required' => 'กรุณากรอกชื่อ',
                'employee_user.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
                'employee_user.unique' => 'ชื่อผู้ใช้งานนี้มีอยู่แล้ว',
                'employee_pass.required' => 'กรุณากรอกรหัสผ่าน',
                'employee_address.required' => 'กรุณากรอกที่อยู่',
                'employee_phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์',
                'employee_facebook.required' => 'กรุณากรอก Facebook ',
                'employee_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'employee_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'employee_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            $employee->employee_name = $request->input('employee_name');
            $employee->employee_user = $request->input('employee_user');

            if ($request->filled('employee_pass')) {
                $employee->employee_pass = md5($request->input('employee_pass'));
            }

            $employee->employee_address = $request->input('employee_address');
            $employee->employee_phone = $request->input('employee_phone');
            $employee->employee_facebook = $request->input('employee_facebook');
            $employee->employee_lineid = $request->input('employee_lineid');

            // Upload and save the employee_img if provided
            if ($request->hasFile('employee_img')) {
                Helper::deleteFile($employee->employee_img);
                $employee->employee_img = Helper::uploadFile($request, "employee_img");
            }

            // Remove file
            if ($request->input('set') === 'file_null') {
                Helper::deleteFile($employee->employee_img);
                $employee->employee_img = "";
            }

            $employee->save();
            return response()->json(['success' => true, 'data' => $employee]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function deleteEmployee($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return response()->json(['success' => true], 200);
        } catch (ValidationException $exception) {
            return response()->json(['success' => false, 'errors' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
