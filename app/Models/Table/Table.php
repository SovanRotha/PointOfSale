<?php

namespace App\Models\Table;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    //
    protected $table = 'tables';

    protected $fillable = [
        'name',
        'capacity',
        'status',
        'location',
    ];

    public function order(){
        
        return $this->hasMany(Order::class);
    }

    
}
