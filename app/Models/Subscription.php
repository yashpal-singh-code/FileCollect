<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'type', // monthly / yearly
        'plan_id', // Razorpay plan_id (plan_xxx)
        'razorpay_subscription_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'status', // created, active, cancelled, expired
        'ends_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'ends_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isExpired(): bool
    {
        return $this->ends_at && now()->greaterThan($this->ends_at);
    }

    /*
    |--------------------------------------------------------------------------
    | Plan Resolver
    |--------------------------------------------------------------------------
    */

    public function plan()
    {
        return Plan::where(function ($query) {
            $query->where('razorpay_plan_monthly', $this->plan_id)
                ->orWhere('razorpay_plan_yearly', $this->plan_id);
        })->first();
    }
}
