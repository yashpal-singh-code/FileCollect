<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class Client extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $guard = 'client';

    protected $fillable = [
        'uuid',
        'owner_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company_name',
        'password',
        'portal_password_set_at',
        'portal_enabled',
        'portal_invite_token',
        'portal_invited_at',
        'portal_last_login_at',
        'status',
        'is_active',
        'last_activity_at',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'notes',
    ];

    protected $hidden = [
        'password',
        'portal_invite_token',
    ];

    protected $casts = [
        'portal_enabled'         => 'boolean',
        'is_active'              => 'boolean',
        'portal_password_set_at' => 'datetime',
        'portal_invited_at'      => 'datetime',
        'portal_last_login_at'   => 'datetime',
        'last_activity_at'       => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto UUID
        static::creating(function ($client) {
            if (empty($client->uuid)) {
                $client->uuid = (string) Str::uuid();
            }
        });

        // Safe password hashing
        static::saving(function ($client) {
            if ($client->isDirty('password') && $client->password) {

                if (!Str::startsWith($client->password, '$2y$')) {
                    $client->password = Hash::make($client->password);
                }

                $client->portal_password_set_at = now();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function accountOwner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('status', 'active');
    }

    public function scopePortalEnabled($query)
    {
        return $query->where('portal_enabled', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /*
    |--------------------------------------------------------------------------
    | Portal Security
    |--------------------------------------------------------------------------
    */
    public function hasPortalAccess(): bool
    {
        return (bool) $this->portal_enabled;
    }

    public function generatePortalInvite(): string
    {
        $rawToken = Str::random(64);

        $this->update([
            'portal_invite_token' => hash('sha256', $rawToken),
            'portal_invited_at'   => now(),
        ]);

        return $rawToken;
    }

    public function ensurePortalInvite(): void
    {
        if ($this->portal_enabled) {
            return;
        }

        if (!$this->portal_invite_token) {
            $this->generatePortalInvite();
        }
    }

    public function recordLogin(): void
    {
        $this->update([
            'portal_last_login_at' => now(),
            'last_activity_at'     => now(),
        ]);
    }

    public function recordActivity(): void
    {
        $this->update([
            'last_activity_at' => now(),
        ]);
    }

    public function markAsActive(): void
    {
        $this->update([
            'status'    => 'active',
            'is_active' => true,
        ]);
    }

    public function markAsBlocked(): void
    {
        $this->update([
            'status'    => 'blocked',
            'is_active' => false,
        ]);
    }


    public function hasPassword(): bool
    {
        return !empty($this->password);
    }
}
