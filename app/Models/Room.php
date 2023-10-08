<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $primaryKey = 'room_id';

    protected $fillable = [
        'room_name',
        'room_type',
        'room_price',
        'room_detail',
        'room_img',
        'room_limit',
    ];

    public function rents()
    {
        return $this->hasMany(Rent::class, 'room_id');
    }
    
}
