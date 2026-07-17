<?php

namespace App\Models\SystemLog;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];


}
