<?php

namespace App\Models\DiscountLog;

use App\Models\Order\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Discount_log extends Model
{
    //
    protected $table = 'discounts_log';

    protected $fillable = [
        'order_id',
        'discount_type',
        'amount',
        'reason',
        'user_id',
        'applied_at',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
