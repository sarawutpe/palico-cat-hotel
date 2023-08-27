<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckinCat extends Model
{
    use HasFactory;

    protected $table = 'checkin_cats';
    protected $primaryKey = 'checkin_cat_id';

    protected $fillable = [
        'checkin_id',
        'cat_id',
    ];
}
