<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\DiscountLog\Discount_log;
use App\Models\Order\Order;
use App\Models\Payment\Payment;
use App\Models\StockLog\StockLogs;
use App\Models\SystemLog\ActivityLog;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

// #[Fillable(['name', 'email', 'password'])]
// #[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function order(){
        
        return $this->hasMany(Order::class);
    }

    public function discountLog()
    {
        return $this->hasMany(Discount_log::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function stocklog(){
        return $this->hasMany(StockLogs::class);
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class);
    }
    
    
    
}
