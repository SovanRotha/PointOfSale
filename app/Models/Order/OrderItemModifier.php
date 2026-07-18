<?php

namespace App\Models\Order;

use App\Models\Modifier\Modifier;
use Illuminate\Database\Eloquent\Model;

class OrderItemModifier extends Model
{
    //
    protected $table = 'order_item_modifiers';

    protected $fillable = [
        'order_item_id',
        'modifier_id',
        'price',
        'quantity'
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function modifier()
    {
        return $this->belongsTo(Modifier::class, 'modifier_id');
    }

    

}
