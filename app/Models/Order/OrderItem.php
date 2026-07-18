<?php

namespace App\Models\Order;

use App\Models\Item\MenuItem;
use App\Models\Modifier\Modifier;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'unit_price',
        'notes',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
  
    public function orderItemModifier()
    {
        return $this->hasMany(OrderItemModifier::class);
    }

    public function modifiers()
    {
        return $this->hasMany(OrderItemModifier::class);
    }
}
