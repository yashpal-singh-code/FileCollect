<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentRequestEvent extends Model
{
    use HasFactory;

    public $timestamps = false; // only created_at

    protected $fillable = [
        'document_request_id',
        'owner_id',
        'event',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'meta'       => 'array',
        'created_at' => 'datetime',
    ];

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

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    // Tenant isolation scope
    public function scopeOwnedBy($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    // Quick static logger
    public static function log(
        DocumentRequest $request,
        string $event,
        array $meta = []
    ): void {
        static::create([
            'document_request_id' => $request->id,
            'owner_id'            => $request->owner_id,
            'event'               => $event,
            'meta'                => $meta ?: null,
        ]);
    }
}
