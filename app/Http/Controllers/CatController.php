<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Http\Helpers\Helper;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class CatController extends Controller
{
    public function getAllCat(Request $request)
    {
        try {
            $q = $request->input('q', '');

            $cats = Cat::with('member')
                ->where('cat_name', 'like', '%' . $q . '%')
                ->orderBy('updated_at', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $cats]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function addCat(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'member_id' => 'required|string',
                'cat_name' => 'required|string',
                'cat_age' => 'required|string',
                'cat_sex' => 'required|string',
                'cat_color' => 'required|string',
                'cat_gen' => 'required|string',
                'cat_accessory' => 'nullable|string',
                'cat_ref' => 'nullable|string',
                'cat_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'member_id.required' => 'กรุณากรอกรหัสสมาชิก',
                'cat_name.required' => 'กรุณากรอกชื่อ',
                'cat_age.required' => 'กรุณากรอกอายุ',
                'cat_sex.required' => 'กรุณากรอกเพศ',
                'cat_color.required' => 'กรุณากรอกสี',
                'cat_gen.required' => 'กรุณากรอกชื่อพันธุ์',
                'cat_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'cat_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'cat_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            $cat = new Cat([
                'cat_name' => $request->input('cat_name'),
                'cat_age' => $request->input('cat_age'),
                'cat_sex' => $request->input('cat_sex'),
                'cat_color' => $request->input('cat_color'),
                'cat_gen' => $request->input('cat_gen'),
                'cat_accessory' => $request->input('cat_accessory'),
                'cat_ref' => $request->input('cat_ref'),
                'member_id' => $request->input('member_id'),
            ]);

            // Upload and save the cat_img if provided
            if ($request->hasFile('cat_img')) {
                $cat->cat_img = Helper::uploadFile($request, "cat_img");
            }

            $cat->save();
            return response()->json(['success' => true, 'data' => $cat]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateCat(Request $request, $id)
    {
        try {
            $cat = Cat::findOrFail($id);
            // Validate the input
            $request->validate([
                'cat_name' => 'required|string',
                'cat_age' => 'required|string',
                'cat_sex' => 'required|string',
                'cat_color' => 'required|string',
                'cat_gen' => 'required|string',
                'cat_accessory' => 'nullable|string',
                'cat_ref' => 'nullable|string',
                'cat_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'cat_name.required' => 'กรุณากรอกชื่อ',
                'cat_age.required' => 'กรุณากรอกอายุ',
                'cat_sex.required' => 'กรุณากรอกเพศ',
                'cat_color.required' => 'กรุณากรอกสี',
                'cat_gen.required' => 'กรุณากรอกชื่อพันธุ์',
                'cat_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'cat_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'cat_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            $cat->cat_name = $request->input('cat_name');
            $cat->cat_age = $request->input('cat_age');
            $cat->cat_sex = $request->input('cat_sex');
            $cat->cat_color = $request->input('cat_color');
            $cat->cat_gen = $request->input('cat_gen');
            $cat->cat_accessory = $request->input('cat_accessory');
            $cat->cat_ref = $request->input('cat_ref');

            // Upload and save the cat_img if provided
            if ($request->hasFile('cat_img')) {
                Helper::deleteFile($cat->cat_img);
                $cat->cat_img = Helper::uploadFile($request, "cat_img");
            }

            // Remove file
            if ($request->input('set') === 'file_null') {
                Helper::deleteFile($cat->cat_img);
                $cat->cat_img = "";
            }

            $cat->save();
            return response()->json(['success' => true, 'data' => $cat]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function deleteCat($id)
    {
        try {
            $cat = Cat::findOrFail($id);
            $cat->delete();
            return response()->json(['success' => true], 200);
        } catch (ValidationException $exception) {
            return response()->json(['success' => false, 'errors' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
