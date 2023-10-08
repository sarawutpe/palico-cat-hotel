<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Helpers\Helper;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function getAllRoom(Request $request)
    {
        try {
            $q = $request->input('q', '');

            $room_type = $request->input('room_type', '');
            $start_date = $request->input('start_date', '');
            $end_date = $request->input('end_date', '');
            $query = Room::with('rent');

            if ($start_date && $end_date) {
                $in_datetime = Carbon::createFromFormat('Y-m-d', $start_date);
                $out_datetime = Carbon::createFromFormat('Y-m-d', $end_date);

                $query->orWhere(function ($subquery) use ($room_type, $in_datetime, $out_datetime) {
                    $subquery->where('room_type', $room_type);
                    $subquery->whereHas('rent', function ($rentQuery) use ($in_datetime, $out_datetime) {
                        $rentQuery->whereDate('in_datetime', '>=', $in_datetime)
                            ->whereDate('out_datetime', '<=', $out_datetime);
                    });
                });

                $query->orWhereDoesntHave('rent');
            }

            if ($room_type) {
                $query->where('room_type', '=', $room_type);
            }

            $query->where('room_name', 'like', '%' . $q . '%');
            $query->orderBy('updated_at', 'desc');
            $rooms  = $query->get();
            return response()->json(['success' => true, 'data' => $rooms]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function addRoom(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'room_name' => 'required|string',
                'room_type' => 'required|string',
                'room_price' => 'required|string',
                'room_limit' => 'required|string',
                'room_detail' => 'nullable|string',
                'room_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'room_name.required' => 'กรุณากรอกชื่อ',
                'room_type.required' => 'กรุณากรอกขนาดหัอง',
                'room_price.required' => 'กรุณากรอกราคา',
                'room_limit.required' => 'กรุณากรอกจำกัดจำนวนแมว',
                'room_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'room_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'room_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            $room = new Room();
            $room->room_name = $request->input('room_name');
            $room->room_type = $request->input('room_type');
            $room->room_price = $request->input('room_price');
            $room->room_limit = $request->input('room_limit');
            $room->room_detail = $request->input('room_detail');

            // Upload and save the room_img if provided
            if ($request->hasFile('room_img')) {
                $room->room_img = Helper::uploadFile($request, "room_img");
            }

            $room->save();
            return response()->json(['success' => true, 'data' => $room]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateRoom(Request $request, $id)
    {
        try {
            $room = Room::findOrFail($id);
            // Validate the input
            $request->validate([
                'room_name' => 'required|string',
                'room_type' => 'required|string',
                'room_price' => 'required|string',
                'room_limit' => 'required|string',
                'room_detail' => 'nullable|string',
                'room_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'room_name.required' => 'กรุณากรอกชื่อ',
                'room_type.required' => 'กรุณากรอกขนาดหัอง',
                'room_price.required' => 'กรุณากรอกราคา',
                'room_limit.required' => 'กรุณากรอกจำกัดจำนวนแมว',
                'room_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'room_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'room_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            $room->room_name = $request->input('room_name');
            $room->room_type = $request->input('room_type');
            $room->room_price = $request->input('room_price');
            $room->room_limit = $request->input('room_limit');
            $room->room_detail = $request->input('room_detail');

            // Upload and save the room_img if provided
            if ($request->hasFile('room_img')) {
                Helper::deleteFile($room->room_img);
                $room->room_img = Helper::uploadFile($request, "room_img");
            }

            // Remove file
            if ($request->input('set') === 'file_null') {
                Helper::deleteFile($room->room_img);
                $room->room_img = "";
            }

            $room->save();
            return response()->json(['success' => true, 'data' => $room]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function deleteRoom($id)
    {
        try {
            $room = Room::findOrFail($id);
            $room->delete();
            return response()->json(['success' => true], 200);
        } catch (ValidationException $exception) {
            return response()->json(['success' => false, 'errors' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
