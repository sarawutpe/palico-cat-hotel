<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    public $incrementing = true;

    protected $table = 'rents';
    protected $primaryKey = 'rent_id';

    protected $fillable = [
        'rent_datetime',
        'rent_status',
        'rent_price',
        'in_datetime',
        'out_datetime',
        'member_id',
        'employee_in',
        'employee_pay',
        'room_id',
        'pay_status'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function service()
    {
        return $this->hasOne(Service::class, 'rent_id');
    }

    public function service_lists()
    {
        return $this->hasMany(ServiceList::class, 'service_id');
    }

    public function checkin() {
        return $this->hasOne(Checkin::class, 'rent_id');
    }
}
