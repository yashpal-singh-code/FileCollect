<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable //implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, TwoFactorAuthenticatable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'uuid',
        'is_owner',
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'phone',
        'job_title',
        'created_by',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'last_activity_at',
        'terms_accepted_at',
        'plan_id',
        'billing_cycle',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
            'is_owner' => 'boolean',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teamMembers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'owner_id');
    }

    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class, 'owner_id');
    }

    public function companySetting()
    {
        return $this->hasOne(CompanySetting::class, 'owner_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Account Owner Resolver
    |--------------------------------------------------------------------------
    */

    public function getAccountOwner(): self
    {
        return $this->is_owner ? $this : ($this->owner ?? $this);
    }

    /*
    |--------------------------------------------------------------------------
    | Current Plan (Stripe → Fallback Free)
    |--------------------------------------------------------------------------
    */
    public function currentPlan(): ?Plan
    {
        $owner = $this->getAccountOwner();

        $subscription = Subscription::where('user_id', $owner->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if ($subscription) {

            return Plan::where(function ($query) use ($subscription) {
                $query->where('razorpay_plan_monthly', $subscription->plan_id)
                    ->orWhere('razorpay_plan_yearly', $subscription->plan_id);
            })->first();
        }

        // Fallback → Free Plan
        return Plan::where('is_free', true)->first();
    }

    public function getActivePlanName(): string
    {
        return optional($this->currentPlan())->name . ' Plan';
    }

    /*
    |--------------------------------------------------------------------------
    | Feature Access
    |--------------------------------------------------------------------------
    */

    public function hasFeature(string $feature): bool
    {
        $owner = $this->getAccountOwner();
        $plan = $owner->currentPlan();

        if (!$plan) {
            return false;
        }

        return (bool) ($plan->{$feature} ?? false);
    }

    /*
    |--------------------------------------------------------------------------
    | Limit Check
    |--------------------------------------------------------------------------
    */

    public function canUse(string $field): bool
    {
        $owner = $this->getAccountOwner();
        $plan = $owner->currentPlan();

        if (!$plan) {
            return false;
        }

        $limit = $plan->{$field} ?? null;

        if (is_null($limit)) {
            return true;
        }

        $current = $owner->getUsageCount($field);

        return $current < $limit;
    }

    /*
    |--------------------------------------------------------------------------
    | Usage Counter
    |--------------------------------------------------------------------------
    */

    public function getUsageCount(string $field): int
    {
        $owner = $this->getAccountOwner();

        return match ($field) {

            // Company Users (owner + active team)
            'company_users' =>
            User::where(function ($query) use ($owner) {
                $query->where('id', $owner->id)
                    ->orWhere('created_by', $owner->id);
            })
                ->whereNull('deleted_at')
                ->count(),

            // Active Clients
            'clients' =>
            Client::where('owner_id', $owner->id)
                ->where('status', 'active')
                ->count(),

            // Monthly Document Requests
            'document_requests' =>
            DocumentRequest::where('owner_id', $owner->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            // Monthly WhatsApp Usage
            'whatsapp_limit' =>
            DocumentRequest::where('owner_id', $owner->id)
                ->whereNotNull('whatsapp_sent_at')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            // Template Count
            'template_limit' =>
            Template::where('created_by', $owner->id)->count(),

            default => 0,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Storage Usage
    |--------------------------------------------------------------------------
    */

    public function getStorageUsedBytes(): int
    {
        $owner = $this->getAccountOwner();

        return File::where('owner_id', $owner->id)
            ->sum('file_size');
    }

    public function hasStorageSpace(): bool
    {
        $plan = $this->currentPlan();

        if (!$plan) {
            return false;
        }

        $used = $this->getStorageUsedBytes();
        $limit = $plan->storage_mb * 1024 * 1024;

        return $used < $limit;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->latest();
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function getNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->implode('');
    }
}
