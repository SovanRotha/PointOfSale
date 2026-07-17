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
    ];

    public function orderItem()
    {
        return $this->belongsToMany(OrderItem::class, 'order_item_modifiers', 'order_item_id', 'modifier_id')->withPivot('price');        
    }

    public function modifier()
    {
        return $this->belongsToMany(Modifier::class, 'order_item_modifiers', 'modifier_id', 'order_item_id')->withPivot('price');
    }

    

}
