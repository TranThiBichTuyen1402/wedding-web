<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wedding_card_id',
        'order_code',
        'amount',
        'payment_method',
        'status',
        'paid_at',
        'note',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Đơn hàng thuộc về 1 Người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Đơn hàng gắn liền với 1 Thiệp cưới (nếu có)
    public function weddingCard()
    {
        return $this->belongsTo(WeddingCard::class);
    }
}