<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\OrderStatus;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function markAsPaid(){
        $this->update(['status' => OrderStatus::PAID->value]);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => OrderStatus::FAILED->value]);
    }
}
