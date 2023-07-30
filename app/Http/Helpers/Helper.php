<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Helper
{
  public static function random($length = 10)
  {
    return Str::random($length);
  }

  public static function uploadFile($request, $fileName)
  {
    if (!$request->hasFile($fileName)) {
      return null;
    }

    $uploadedFile = $request->file($fileName);
    $filename = self::random(10) . '.' . $uploadedFile->getClientOriginalExtension();
    $uploadedFile->storeAs('', $filename, 'public');

    // Delete current file name if not exists.
    self::deleteFile($request->$fileName);

    return $filename;
  }

  public static function deleteFile($fileName)
  {
    if (!$fileName) return false;

    // Check if the file exists in the storage
    $filePath = '/' . $fileName;
    if (Storage::disk('public')->exists($filePath)) {
      // Delete the file from the storage
      Storage::disk('public')->delete($filePath);
      return true;
    }

    return false;
  }
}
