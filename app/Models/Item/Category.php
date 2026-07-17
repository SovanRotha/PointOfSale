<?php

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    public function menuItem(){
        return $this->hasMany(MenuItem::class, 'category_id');
    }

    
}
