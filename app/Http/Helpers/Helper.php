<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\Session;

class Helper
{

  public static function is_member()
  {
    return Session::get('type') === Key::$member;
  }

  public  static function is_employee()
  {
    return Session::get('type') === Key::$employee;
  }

  public static function is_admin()
  {
    return Session::get('type') === Key::$admin;
  }

  public static function random($length = 10)
  {
    return Str::random($length);
  }

  // $request is request data
  // $fileName is key name file
  public static function uploadFile($request, $fileName)
  {
    if (!$request->hasFile($fileName)) {
      return null;
    }

    $uploadedFile = $request->file($fileName);
    $filename = self::random(10) . '.' . $uploadedFile->getClientOriginalExtension();
    $uploadedFile->storeAs('', $filename, 'public');

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
