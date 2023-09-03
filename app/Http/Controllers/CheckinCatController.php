<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckinCat;
use Illuminate\Validation\ValidationException;

class CheckinCatController extends Controller
{
    public function addCheckinCat(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'rent_id' => 'required|string',
                'cat_id' => 'required|array',
                'cat_id.*' => 'string',
            ], [
                'rent_id.required' => 'กรุณากรอกรหัสการจอง',
                'cat_id.required' => 'กรุณากรอกแมว',
                'cat_id.array' => 'รหัสแมวต้องเป็นอาร์เรย์',
                'cat_id.*.string' => 'รหัสแมวแต่ละรายการต้องเป็นสตริง',
            ]);

            $rent_id = $request->input('rent_id');
            $cat_ids = $request->input('cat_id');

            foreach ($cat_ids as $cat_id) {
                $checkin_cat = new CheckinCat();
                $checkin_cat->rent_id = $rent_id;
                $checkin_cat->cat_id = $cat_id;
                $checkin_cat->save();
            }

            return response()->json(['success' => true, 'data' => $checkin_cat]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateCheckinCat(Request $request, $id)
    {
        try {
            // Validate the input
            $request->validate([
                'cat_id' => 'required|array',
                'cat_id.*' => 'string',
            ], [
                'cat_id.required' => 'กรุณากรอกแมว',
                'cat_id.array' => 'รหัสแมวต้องเป็นอาร์เรย์',
                'cat_id.*.string' => 'รหัสแมวแต่ละรายการต้องเป็นสตริง',
            ]);
            $cat_ids = $request->input('cat_id');

            // Remove and clear old checkin cat
            $checkin_cat_old = CheckinCat::where('rent_id', $id)->get();
            foreach ($checkin_cat_old as $checkin_cat) {
                $checkin_cat->delete();
            }

            // Add new checkin cat
            foreach ($cat_ids as $cat_id) {
                $checkin_cat = new CheckinCat();
                $checkin_cat->rent_id = $id;
                $checkin_cat->cat_id = $cat_id;
                $checkin_cat->save();
            }

            return response()->json(['success' => true, 'data' => $checkin_cat]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
