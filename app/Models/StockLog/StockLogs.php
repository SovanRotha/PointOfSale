<?php

namespace App\Models\StockLog;

use App\Models\Item\MenuItem;
use App\Models\Order\Order;
use App\Models\Payment\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StockLogs extends Model
{
    //
    protected $table = 'stock_logs';

    protected $fillable = [
        'menu_item_id',
        'previous_stock',
        'new_stock',
        'quantity_change',
        'change_type',
        'reason',
        'user_id',
        'order_id',
        'notes',
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payment(){
        return $this->belongsTo(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
