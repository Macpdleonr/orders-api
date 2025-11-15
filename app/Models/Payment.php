<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'success',
        'external_transaction_id',
        'response_payload',
    ];

    protected $casts = [
        'success' => 'boolean',
        'response_payload' => 'array',
        'amount' => 'decimal:2',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
