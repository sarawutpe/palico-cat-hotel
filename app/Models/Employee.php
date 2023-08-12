<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'employee_name',
        'employee_user',
        'employee_pass',
        'employee_email',
        'employee_address',
        'employee_phone',
        'employee_facebook',
        'employee_lineid',
        'employee_img',
    ];
}
