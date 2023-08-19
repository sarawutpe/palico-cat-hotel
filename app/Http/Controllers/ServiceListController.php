<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceList;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use DateTime;

class ServiceListController extends Controller
{
    public function getAllServiceListByService($id)
    {
        try {
            $service_lists  = ServiceList::where('service_id', $id)->get();
            return response()->json(['success' => true, 'data' => $service_lists]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function addServiceList(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'service_id' => 'required|string',
                'service_list_name' => 'required|string',
                'service_list_datetime' => 'required|string',
                'service_list_checked' => 'required|string',
            ], [
                'service_id.required' => 'กรุณากรอกรหัสรายการบริการ',
                'service_list_name.required' => 'กรุณากรอกชื่อรายการ',
                'service_list_datetime.required' => 'กรุณากรอกเวลา',
                'service_list_checked.required' => 'กรุณากรอกเช็คลิสต์',
            ]);

            $service_list = new ServiceList();
            $service_list->service_list_name = $request->input('service_list_name');
            $service_list->service_list_datetime = $request->input('service_list_datetime');
            $service_list->service_list_checked = $request->input('service_list_checked');
            $service_list->save();
            return response()->json(['success' => true, 'data' => $service_list]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateServiceList(Request $request, $id)
    {
        try {
            $service_list = ServiceList::findOrFail($id);

            // Validate the input
            $request->validate([
                'service_list_name' => 'required|string',
                'service_list_datetime' => 'required|string',
                'service_list_checked' => 'required|string',
            ], [
                'service_list_name.required' => 'กรุณากรอกชื่อรายการ',
                'service_list_datetime.required' => 'กรุณากรอกเวลา',
                'service_list_checked.required' => 'กรุณากรอกเช็คลิสต์',
            ]);

            $service_list->service_list_name = $request->input('service_list_name');
            $service_list->service_list_datetime =  Carbon::parse($request->input('service_list_datetime'));
            $service_list->service_list_checked = $request->input('service_list_checked');
            $service_list->save();

            return response()->json(['success' => true, 'data' => $service_list]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function deleteServiceList($id)
    {
        try {
            $room = ServiceList::findOrFail($id);
            $room->delete();
            return response()->json(['success' => true], 200);
        } catch (ValidationException $exception) {
            return response()->json(['success' => false, 'errors' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
