<?php

namespace App\Models\Modifier;

use App\Http\Controllers\Order\OrderItemModifier;
use App\Models\Item\MenuItem;
use App\Models\Order\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    //
    protected $table = 'modifiers';

    protected $fillable = [
        'name',
        'price',
        'menu_item_id',
    ];

    public function menuItem(){
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'order_item_id');
    }
    public function orderItemModifier()
    {
        return $this->hasMany(OrderItemModifier::class, 'modifier_id');
    }
}
