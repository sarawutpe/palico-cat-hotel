<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

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

    self::deleteFile($request->$fileName);

    return $filename;
  }

  public static function deleteFile($fileName)
  {

    if (Storage::disk('public')->exists($fileName)) {
      Storage::disk('public')->delete($fileName);
      return true;
    }

    return false;
  }
}
