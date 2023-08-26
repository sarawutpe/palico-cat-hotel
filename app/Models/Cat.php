<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;

    protected $table = 'cats';
    protected $primaryKey = 'cat_id';

    protected $fillable = [
        'member_id',
        'cat_name',
        'cat_age',
        'cat_sex',
        'cat_color',
        'cat_gen',
        'cat_ref',
        'cat_img',
        'cat_accessory',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
