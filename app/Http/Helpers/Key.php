<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Key
{
  public static $member = 'MEMBER';
  public static $employee = 'EMPLOYEE';
  public static $admin = 'ADMIN';

  public static $PENDING = 'PENDING';
  public static $RESERVED = 'RESERVED';
  public static $CHECKED_IN = 'CHECKED_IN';
  public static $CHECKED_OUT = 'CHECKED_OUT';
  public static $CANCELED = 'CANCELED';
  public static $PAYING = 'PAYING';
  public static $COMPLETED = 'COMPLETED';
}
