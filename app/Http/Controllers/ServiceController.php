<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    public function getAllService()
    {
        try {
            $service_lists  = Service::with('service_lists')->get();
            return response()->json(['success' => true, 'data' => $service_lists]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function addService(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'rent_id' => 'required|string',
                'service_detail' => 'nullable|string',
            ], [
                'rent_id.required' => 'กรุณากรอกรหัสการเช่า'
            ]);

            // If exists
            $service_exists = Service::with('service_lists')->where('rent_id', $request->input('rent_id'))->get()->first();
            if ($service_exists) {
                return response()->json(['success' => true, 'data' => $service_exists]);
            }

            // If create a new
            $service = new Service();
            $service->rent_id = $request->input('rent_id');
            $service->service_detail = $request->input('service_detail');
            $service->save();

            return response()->json(['success' => true, 'data' => $service]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
