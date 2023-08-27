<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkin;
use Illuminate\Validation\ValidationException;

class CheckinController extends Controller
{
    public function addCheckin(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'rent_id' => 'required|string',
                'checkin_detail' => 'nullable|string',
                'checkin_status' => 'required|string',
            ], [
                'rent_id.required' => 'กรุณากรอกรหัสการจอง',
                'checkin_status.required' => 'กรุณากรอกสถานะ'
            ]);

            $checkin = new Checkin();
            $checkin->rent_id = $request->input('rent_id');
            $checkin->checkin_detail = $request->input('checkin_detail');
            $checkin->checkin_status = $request->input('checkin_status');
            $checkin->save();

            return response()->json(['success' => true, 'data' => $checkin]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateCheckin(Request $request, $id)
    {
        try {
            // Validate the input
            $request->validate([
                'rent_id' => 'required|string',
                'checkin_detail' => 'nullable|string',
                'checkin_status' => 'required|string',
            ], [
                'rent_id.required' => 'กรุณากรอกรหัสการจอง',
                'checkin_status.required' => 'กรุณากรอกสถานะ'
            ]);

            $checkin = Checkin::findOrFail($id);
            $checkin->rent_id = $request->input('rent_id');
            $checkin->checkin_detail = $request->input('checkin_detail');
            $checkin->checkin_status = $request->input('checkin_status');
            $checkin->save();

            return response()->json(['success' => true, 'data' => $checkin]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
