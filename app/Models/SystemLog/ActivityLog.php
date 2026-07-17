<?php

namespace App\Models\SystemLog;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    //
    protected $table = 'acitivies_log';

    protected $fillable = [
        'user_id',
        'activity',
        'ip_address',
        'device',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
