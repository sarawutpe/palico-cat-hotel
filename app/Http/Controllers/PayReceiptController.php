<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayReceipt;
use Illuminate\Validation\ValidationException;

class PayReceiptController extends Controller
{
    public function getAllRent()
    {
        try {
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getPayReceiptByRent($id)
    {
        try {
            $pay_receipt = PayReceipt::where('rent_id', $id)
                ->orderBy('updated_at', 'desc')
                ->first();
            return response()->json(['success' => true, 'data' => $pay_receipt]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
