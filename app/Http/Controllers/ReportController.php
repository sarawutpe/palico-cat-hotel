<?php

namespace App\Http\Controllers;


use App\Models\Member;
use App\Models\Room;
use App\Models\Cat;
use App\Models\Rent;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use stdClass;

class ReportController extends Controller
{
    public function getAllStats(Request $request)
    {
        try {
            $q = $request->input('q', '');

            $day = null;
            $month = null;
            $year = null;

            if ($q === 'd') {
                $day = now()->day;
            } elseif ($q === 'm') {
                $month = now()->month;
            } elseif ($q === 'y') {
                $year = now()->year;
            }

            $data = new stdClass();
            $data->user_count = Member::count();
            $data->room_count = Room::count();
            $data->cat_count = Cat::count();
            $data->income_count = Rent::where('rent_status', 'CHECKED_OUT')
                ->where('pay_status', 'PAYING')
                ->sum('rent_price');

            $income_stats = Rent::select(
                DB::raw('DATE(in_datetime) as day'),
                DB::raw('SUM(rent_price) as income_count')
            )
                ->where('rent_status', 'CHECKED_OUT')
                ->where('pay_status', 'PAYING')
                ->when($day, function ($query, $day) {
                    return $query->whereDay('in_datetime', $day);
                })
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('in_datetime', $month);
                })
                ->when($year, function ($query, $year) {
                    return $query->whereYear('in_datetime', $year);
                })
                ->groupBy('day')
                ->get();

            $data->income_stats = $income_stats;
            return response()->json(['success' => true, 'data' => $data]);
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
