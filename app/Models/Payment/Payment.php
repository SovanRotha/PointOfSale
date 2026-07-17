<?php

namespace App\Models\Payment;

use App\Models\Order\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'transaction_reference',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function paid_by()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
