<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $primaryKey = 'member_id'; 

    protected $fillable = [
        'member_name',
        'member_user',
        'member_pass',
        'member_email',
        'member_address',
        'member_phone',
        'member_facebook',
        'member_lineid',
        'member_img',
    ];
}
