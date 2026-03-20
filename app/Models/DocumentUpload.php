<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentUpload extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_request_id',
        'owner_id',
        'uploaded_by',
        'field_label',
        'file_path',
        'disk',
        'original_name',
        'mime_type',
        'file_extension',
        'file_hash',
        'file_size',
        'ip_address',
        'user_agent',
        'uploaded_at',
    ];

    protected $casts = [
        'file_size'   => 'integer',
        'uploaded_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot - Auto Sync Storage
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        // When upload is created
        static::created(function ($upload) {

            if ($upload->file_size > 0 && $upload->documentRequest) {
                $upload->documentRequest->incrementUploadSize(
                    $upload->file_size
                );
            }
        });

        // When upload is soft deleted
        static::deleted(function ($upload) {

            if ($upload->file_size > 0 && $upload->documentRequest) {
                $upload->documentRequest->decrement('upload_count');
                $upload->documentRequest->decrement(
                    'total_upload_size',
                    $upload->file_size
                );
            }
        });

        // When upload is restored
        static::restored(function ($upload) {

            if ($upload->file_size > 0 && $upload->documentRequest) {
                $upload->documentRequest->incrementUploadSize(
                    $upload->file_size
                );
            }
        });
    }
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Parent document request
    public function documentRequest()
    {
        return $this->belongsTo(DocumentRequest::class);
    }

    // Tenant owner
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Who uploaded (team member)
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    // Tenant isolation
    public function scopeOwnedBy($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    // File size in MB
    public function getFileSizeMBAttribute(): float
    {
        return round($this->file_size / 1024 / 1024, 2);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}