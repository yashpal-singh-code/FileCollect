<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class DocumentRequest extends Model
{
    use HasFactory, SoftDeletes;

    // Status constants (must match ENUM exactly)
    public const STATUS_DRAFT       = 'draft';
    public const STATUS_SENT        = 'sent';
    public const STATUS_VIEWED      = 'viewed';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED   = 'completed';
    public const STATUS_EXPIRED     = 'expired';
    public const STATUS_CANCELLED   = 'cancelled';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_SENT,
        self::STATUS_VIEWED,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
        self::STATUS_EXPIRED,
        self::STATUS_CANCELLED,
    ];

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'owner_id',
        'uuid',
        'request_number',
        'requested_by',
        'client_id',
        'template_uuid',
        'fields',
        'status',
        'sent_at',
        'viewed_at',
        'last_viewed_at',
        'completed_at',
        'expires_at',
        'last_activity_at',
        'whatsapp_sent_at',
        'access_token',
        'portal_url',
        'disk',
        'upload_count',
        'total_fields',
        'total_upload_size',
        'message',
        'ip_address',
        'user_agent',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'fields'            => 'array',
        'sent_at'           => 'datetime',
        'viewed_at'         => 'datetime',
        'last_viewed_at'    => 'datetime',
        'completed_at'      => 'datetime',
        'expires_at'        => 'datetime',
        'last_activity_at'  => 'datetime',
        'whatsapp_sent_at'  => 'datetime',
        'upload_count'      => 'integer',
        'total_fields'      => 'integer',
        'total_upload_size' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($request) {

            if (empty($request->uuid)) {
                $request->uuid = (string) Str::uuid();
            }

            if (empty($request->access_token)) {
                $plain = Str::random(64);
                $request->access_token = hash('sha256', $plain);
            }

            if (empty($request->status)) {
                $request->status = self::STATUS_DRAFT;
            }

            // DEFAULT EXPIRY 7 DAYS
            if (empty($request->expires_at)) {
                $request->expires_at = now()->addDays(7);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_uuid', 'uuid');
    }

    public function uploads()
    {
        return $this->hasMany(DocumentUpload::class);
    }

    public function events()
    {
        return $this->hasMany(DocumentRequestEvent::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeOwnedBy($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_SENT,
            self::STATUS_VIEWED,
            self::STATUS_IN_PROGRESS,
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }

    /*
    |--------------------------------------------------------------------------
    | Storage Helpers
    |--------------------------------------------------------------------------
    */

    public function incrementUploadSize(int $bytes): void
    {
        $this->increment('upload_count');
        $this->increment('total_upload_size', $bytes);
    }

    public function getTotalUploadSizeMBAttribute(): float
    {
        return round($this->total_upload_size / 1024 / 1024, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Updates
    |--------------------------------------------------------------------------
    */

    public function markInProgress(): void
    {
        $this->update([
            'status' => self::STATUS_IN_PROGRESS,
            'last_activity_at' => now(),
        ]);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'last_activity_at' => now(),
        ]);
    }
}
