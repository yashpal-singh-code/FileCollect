<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Mass Assignment Protection
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'slug',

        // Pricing
        'monthly_price',
        'yearly_price',
        'currency',
        'is_free',

        'is_popular',
        'is_active',
        'sort_order',

        // Limits
        'company_users',
        'clients',
        'document_requests',
        'template_limit',
        'request_templates',
        'storage_mb',
        'file_size_limit_mb',
        'whatsapp_limit',
        'usage_reset_type',

        // Upload controls
        'allowed_mime_types',
        'allow_zip',
        'allow_video',
        'allow_multiple_uploads',

        // Features
        'client_portal',
        'otp_login',
        'approve_workflow',
        'reupload_history',
        'download_zip',
        'expiry_tracking',
        'renewal_reminder',
        'scheduled_reminder',
        'escalation_reminder',
        'export_excel',
        'export_pdf',
        'branding',
        'white_label',
        'priority_support',
    ];

    // Stripe IDs protected from mass assignment
    protected $guarded = [
        'stripe_product_id',
        'stripe_price_monthly',
        'stripe_price_yearly',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'yearly_price'  => 'decimal:2',

        'company_users' => 'integer',
        'clients' => 'integer',
        'document_requests' => 'integer',
        'template_limit' => 'integer',
        'request_templates' => 'integer',
        'storage_mb' => 'integer',
        'file_size_limit_mb' => 'integer',
        'whatsapp_limit' => 'integer',
        'sort_order' => 'integer',

        'allowed_mime_types' => 'array',

        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'is_free' => 'boolean',

        'allow_zip' => 'boolean',
        'allow_video' => 'boolean',
        'allow_multiple_uploads' => 'boolean',

        'client_portal' => 'boolean',
        'otp_login' => 'boolean',
        'approve_workflow' => 'boolean',
        'reupload_history' => 'boolean',
        'download_zip' => 'boolean',
        'expiry_tracking' => 'boolean',
        'renewal_reminder' => 'boolean',
        'scheduled_reminder' => 'boolean',
        'escalation_reminder' => 'boolean',
        'export_excel' => 'boolean',
        'export_pdf' => 'boolean',
        'branding' => 'boolean',
        'white_label' => 'boolean',
        'priority_support' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot Defaults & Hardening
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($plan) {

            // Normalize slug
            $plan->slug = strtolower(trim($plan->slug));

            // Safe defaults
            $plan->storage_mb ??= 0;
            $plan->file_size_limit_mb ??= 0;

            // Secure default MIME types (NO wildcard)
            $plan->allowed_mime_types ??= [
                'application/pdf',
                'image/jpeg',
                'image/png'
            ];

            $plan->is_active ??= true;
            $plan->is_popular ??= false;
            $plan->is_free ??= false;

            $plan->currency ??= 'USD';
            $plan->usage_reset_type ??= 'monthly';
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /*
    |--------------------------------------------------------------------------
    | Stripe Helpers (Hardened)
    |--------------------------------------------------------------------------
    */

    public function getStripePrice(string $billing = 'monthly'): string
    {
        if (!in_array($billing, ['monthly', 'yearly'])) {
            abort(400, 'Invalid billing cycle.');
        }

        $price = $billing === 'yearly'
            ? $this->stripe_price_yearly
            : $this->stripe_price_monthly;

        if (!$price) {
            abort(400, 'Stripe price not configured.');
        }

        return $price;
    }

    /*
    |--------------------------------------------------------------------------
    | Plan Type Helpers
    |--------------------------------------------------------------------------
    */

    public function isFree(): bool
    {
        return $this->is_free === true;
    }

    public function isPaid(): bool
    {
        return !$this->isFree();
    }

    /*
    |--------------------------------------------------------------------------
    | Feature Helpers
    |--------------------------------------------------------------------------
    */

    public function hasFeature(string $feature): bool
    {
        return (bool) ($this->{$feature} ?? false);
    }

    /*
    |--------------------------------------------------------------------------
    | Upload Helpers (Secure)
    |--------------------------------------------------------------------------
    */

    public function maxUploadBytes(): ?int
    {
        if ($this->hasUnlimited('file_size_limit_mb')) {
            return null;
        }

        return (int) $this->file_size_limit_mb * 1024 * 1024;
    }

    public function storageBytes(): ?int
    {
        if ($this->hasUnlimited('storage_mb')) {
            return null;
        }

        return (int) $this->storage_mb * 1024 * 1024;
    }

    public function allowedMimeTypes(): array
    {
        return $this->allowed_mime_types ?? [];
    }

    public function isMimeAllowed(string $mime): bool
    {
        $allowed = $this->allowedMimeTypes();

        return in_array($mime, $allowed, true);
    }

    /*
    |--------------------------------------------------------------------------
    | Limit Helpers
    |--------------------------------------------------------------------------
    */

    public function hasUnlimited(string $column): bool
    {
        return is_null($this->{$column}) || (int) $this->{$column} === 0;
    }

    public function canUse(string $column, int $currentUsage): bool
    {
        if ($this->hasUnlimited($column)) {
            return true;
        }

        return $currentUsage < (int) $this->{$column};
    }

    /*
    |--------------------------------------------------------------------------
    | Pricing Helpers
    |--------------------------------------------------------------------------
    */

    public function yearlySavingsPercentage(): ?int
    {
        if (
            is_null($this->monthly_price) ||
            is_null($this->yearly_price) ||
            (float) $this->monthly_price <= 0
        ) {
            return null;
        }

        $yearlyFromMonthly = (float) $this->monthly_price * 12;

        if ($yearlyFromMonthly <= 0) {
            return null;
        }

        $percentage = (
            ($yearlyFromMonthly - (float) $this->yearly_price)
            / $yearlyFromMonthly
        ) * 100;

        return $percentage > 0
            ? (int) round($percentage)
            : null;
    }
}
