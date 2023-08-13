<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayReceipt extends Model
{
    use HasFactory;

    protected $table = 'pay_receipts';
    protected $primaryKey = 'pay_receipt_id';

    protected $fillable = [
        'rent_id',
        'pay_receipt_datetime',
        'pay_receipt_qr',
    ];
}
