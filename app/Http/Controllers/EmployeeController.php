<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Employee;
use App\Http\Helpers\Helper;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Rules\UniqueUser;


// add get update delete
class EmployeeController extends Controller
{

    public function index()
    {
        try {
            $employees = Employee::all();
            return view('dashboard.employee', compact('employees'));
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function addEmployee(Request $request)
    {
        try {
            $employee_name = $request->input('employee_name');
            $employee_user = $request->input('employee_user');
            $employee_pass = $request->input('employee_pass');
            $employee_address = $request->input('employee_address');
            $employee_phone = $request->input('employee_phone');
            $employee_facebook = $request->input('employee_facebook');
            $employee_lineid = $request->input('employee_lineid');

            // Validate the input
            $request->validate([
                'employee_name' => 'required|string',
                'employee_user' => ['required', new UniqueUser],
                'employee_pass' => 'required|string',
                'employee_address' => 'required|string',
                'employee_phone' => 'required|string',
                'employee_facebook' => 'nullable|string',
                'employee_lineid' => 'nullable|string',
                'employee_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'employee_name.required' => 'กรุณากรอกชื่อสมาชิก',
                'employee_user.required' => 'กรุณากรอกชื่อผู้ใช้งานสมาชิก',
                'employee_user.unique' => 'ชื่อผู้ใช้งานสมาชิกนี้มีอยู่แล้ว',
                'employee_pass.required' => 'กรุณากรอกรหัสผ่านสมาชิก',
                'employee_address.required' => 'กรุณากรอกที่อยู่สมาชิก',
                'employee_phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์สมาชิก',
                'employee_facebook.required' => 'กรุณากรอก Facebook สมาชิก',
                'employee_img.image' => 'รูปภาพของสมาชิกต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'employee_img.mimes' => 'รูปภาพของสมาชิกต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'employee_img.max' => 'ขนาดรูปภาพของสมาชิกต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            // Create a new Member instance and save it to the database
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

            return redirect()->back()->with('success', 'สมัครสมาชิกสำเร็จ');
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
