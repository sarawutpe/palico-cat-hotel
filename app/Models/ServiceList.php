<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceList extends Model
{
    use HasFactory;

    protected $table = 'service_lists';
    protected $primaryKey = 'service_list_id';

    protected $fillable = [
        'service_id',
        'service_list_name',
        'service_list_datetime',
        'service_list_checked',
    ];
}
