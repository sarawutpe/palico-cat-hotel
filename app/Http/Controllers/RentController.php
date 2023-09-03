<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\PayReceipt;
use App\Http\Helpers\Helper;
use App\Http\Helpers\Key;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;

class RentController extends Controller
{
    public function getAllRent(Request $request)
    {
        try {
            $rents = Rent::with('member')
                ->with('room')
                ->with('service')
                ->with('service.service_lists')
                ->with('checkin')
                ->with('checkin_cats')
                ->with('checkin_cats.cat')
                ->orderBy('updated_at', 'desc')
                ->get();
            return response()->json(['success' => true, 'data' => $rents]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getRentById($id)
    {
        try {
            $rent = Rent::where('rent_id', $id)
                ->with('member')
                ->with('room')
                ->with('service')
                ->with('service.service_lists')
                ->with('checkin')
                ->with('checkin_cats')
                ->with('checkin_cats.cat')
                ->orderBy('updated_at', 'desc')
                ->first();
            return response()->json(['success' => true, 'data' => $rent]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function getRentByMember($id)
    {
        try {
            $rooms = Rent::with('room')->where('member_id', $id)->orderBy('updated_at', 'desc')->get();
            return response()->json(['success' => true, 'data' => $rooms]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function addRent(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'rent_datetime' => 'required|string',
                'rent_status' => 'nullable|string',
                'rent_price' => 'required|string',
                'in_datetime' => 'required|string',
                'out_datetime' => 'required|string',
                'member_id' => 'required|string',
                'employee_in' => 'nullable|string',
                'employee_pay' => 'nullable|string',
                'room_id' => 'required|string',
                'pay_status' => 'nullable|string',
                'slip_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'rent_datetime.required' => 'กรุณากรอกชื่อวันที่เช่าพัก',
                'room_type.required' => 'กรุณากรอกขนาดหัอง',
                'room_price.required' => 'กรุณากรอกราคา',
                'rent_datetime.required' => 'กรุณากรอกวันที่เช่าพัก',
                'rent_price.required' => 'กรุณากรอกราคาเช่า',
                'in_datetime.required' => 'กรุณากรอกวันที่เช็คอิน',
                'out_datetime.required' => 'กรุณากรอกวันที่เช็คเอาท์',
                'member_id.required' => 'กรุณากรอกรหัสสมาชิก',
                'room_id.required' => 'กรุณากรอกรหัสห้อง',
                'slip_img.required' => 'กรุณาอัพโหลดสลิป',
                'slip_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'slip_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'slip_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            // Check Rent
            $rent = Rent::where('room_id', $request->input('room_id'))
                ->where('rent_status', Key::$RESERVED)
                ->first();

            if ($rent) {
                return response()->json(['success' => false, 'errors' => 'จองไม่สำเร็จเนื่องจากห้องพักไม่ว่าง'], 400);
            }

            $rent = new Rent();
            $rent->rent_datetime = Carbon::parse($request->input('rent_datetime'));
            $rent->rent_status = 'PENDING';
            $rent->rent_price = $request->input('rent_price');
            $rent->in_datetime = Carbon::parse($request->input('in_datetime'));
            $rent->out_datetime = Carbon::parse($request->input('out_datetime'));
            $rent->member_id = $request->input('member_id');
            $rent->room_id = $request->input('room_id');
            $rent->pay_status = 'PENDING';
            $rent->save();

            // Save Pay Receipt 
            if (isset($rent->rent_id)) {
                $pay_receipt = new PayReceipt();
                $pay_receipt->rent_id = $rent->rent_id;
                $pay_receipt->pay_receipt_datetime = new DateTime();

                // Upload and save the slip_img if provided
                if ($request->hasFile('slip_img')) {
                    $pay_receipt->pay_receipt_qr = Helper::uploadFile($request, "slip_img");
                }

                $pay_receipt->save();
            }

            return response()->json(['success' => true, 'data' => $rent]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateRent(Request $request, $id)
    {
        try {
            $rent = Rent::findOrFail($id);
            // Validate the input
            $request->validate([
                'rent_status' => 'required|string',
                'in_datetime' => 'required|string',
                'out_datetime' => 'required|string',
                'pay_status' => 'required|string',
                'employee_in' => 'required|string',
                'employee_pay' => 'required|string',
            ], [
                'rent_status.required' => 'กรุณากรอกสถานะการจอง ราคาเช่า',
                'in_datetime.required' => 'กรุณากรอกวันที่เช็คอิน',
                'out_datetime.required' => 'กรุณากรอกวันที่เช็คเอาท์',
                'pay_status.required' => 'กรุณากรอกสถานะการจ่ายเงิน',
                'employee_in.required' => 'กรุณากรอกรหัสผู้รับเข้าพัก',
                'employee_pay.required' => 'กรุณากรอกรหัสผู้รับเงิน',
            ]);

            $rent->in_datetime = Carbon::parse($request->input('in_datetime'));
            $rent->out_datetime = Carbon::parse($request->input('out_datetime'));
            $rent->rent_status = $request->input('rent_status');
            $rent->pay_status = $request->input('pay_status');
            $rent->employee_in = $request->input('employee_in');
            $rent->employee_pay = $request->input('employee_pay');
            $rent->save();

            return $this->getRentById($id);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function deleteRent($id)
    {
        try {
            $room = Rent::findOrFail($id);
            $room->delete();
            return response()->json(['success' => true], 200);
        } catch (ValidationException $exception) {
            return response()->json(['success' => false, 'errors' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
