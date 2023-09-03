<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getAllStats(Request $request)
    {
        try {

            return response()->json(['success' => true, 'data' => 'ok']);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getAllIncome(Request $request)
    {
        try {

            return response()->json(['success' => true, 'data' => 'ok']);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
