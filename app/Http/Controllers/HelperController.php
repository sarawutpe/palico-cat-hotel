<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HelperController extends Controller
{
    public function uploadFile(Request $request, String $file)
    {
        if ($request->hasFile($file)) {
            return "";
        }

        $request->validate([
            $file => 'required|file|mimes:jpg,png,pdf',
        ]);

        $file = $request->file('member_img');
        $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('files', $filename, 'public');
        return $filename;
    }

    public function deleteFile(String $file) {

    }
}
