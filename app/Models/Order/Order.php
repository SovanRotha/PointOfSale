<?php

namespace App\Models\Order;

use App\Models\DiscountLog\Discount_log;
use App\Models\Payment\Payment;
use App\Models\StockLog\StockLogs;
use App\Models\Table\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'table_id',
        'status',
        'user_id',
        'sub_total',
        'tax',
        'discount',
        'service_charge',
        'total',
        'notes',
        'voided_reason',
    ];

    

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function discountLog()
    {
        return $this->hasMany(Discount_log::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stocklog(){
        return $this->hasMany(StockLogs::class);
    }
}
