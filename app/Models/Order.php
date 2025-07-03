<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2'
    ];

    // Relationship: Order belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Order has many Order Items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}