<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'emp_name',
        'emp_user',
        'emp_pass',
        'emp_address',
        'emp_phone',
        'emp_facebook',
        'emp_lineid',
        'emp_img',
    ];
}
