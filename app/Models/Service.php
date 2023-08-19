<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $primaryKey = 'service_id';

    protected $fillable = [
        'service_detail',
    ];

    public function service_lists()
    {
        return $this->hasMany(ServiceList::class, 'service_id');
    }
}
