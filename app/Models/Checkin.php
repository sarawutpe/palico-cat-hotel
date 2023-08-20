<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $table = 'checkins';
    protected $primaryKey = 'checkin_id';

    protected $fillable = [
        'checkin_detail',
        'checkin_status',
    ];
}
