<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'admin_name',
        'admin_user',
        'admin_pass',
        'admin_address',
        'admin_phone',
        'admin_facebook',
        'admin_lineid',
        'admin_img',
    ];
}
