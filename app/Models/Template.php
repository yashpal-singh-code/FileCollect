<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'owner_id',
        'created_by',
        'name',
        'description',
        'fields',
        'settings',
        'default_due_days',
        'version',
        'is_locked',
        'is_active',
        'usage_count',
        'last_used_at',
    ];

    protected $casts = [
        'fields' => 'array',
        'settings' => 'array',
        'is_locked' => 'boolean',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    // Auto-generate UUID on create
    protected static function booted()
    {
        static::creating(function ($template) {
            if (empty($template->uuid)) {
                $template->uuid = (string) Str::uuid();
            }
        });
    }

    // Owner relationship
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Creator relationship
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('owner_id', $userId);
    }

    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class, 'template_uuid', 'uuid');
    }
}
