<?php

namespace App\Models\Item;

use App\Models\Modifier\Modifier;
use App\Models\Order\OrderItem;
use App\Models\StockLog\StockLogs;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    //
    protected $table = 'menu_items';

    protected $fillable = [
        'name',
        'description',
        'cost_price',
        'selling_price',
        'category_id',
        'image',
        'sku',
        'barcode',
        'is_active',
        'track_stock',
        'stock_quantity',
        'low_stock_threshold',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function modifier(){
        return $this->hasMany(Modifier::class, 'menu_item_id');
    }

    public function orderItem(){
        return $this->hasMany(OrderItem::class, 'menu_item_id');
    }
    public function stocklog()
    {
        return $this->hasMany(StockLogs::class, 'menu_item_id');
    }

}
