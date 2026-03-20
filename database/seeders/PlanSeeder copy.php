<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $plans = [

                /*
                |--------------------------------------------------------------------------
                | FREE PLAN
                |--------------------------------------------------------------------------
                */
                [
                    'slug' => 'free',
                    'name' => 'Free',

                    'monthly_price' => 0.00,
                    'yearly_price' => 0.00,
                    'currency' => 'USD',
                    'is_free' => true,

                    'is_popular' => false,
                    'sort_order' => 1,
                    'is_active' => true,

                    'stripe_product_id' => null,
                    'stripe_price_monthly' => null,
                    'stripe_price_yearly' => null,

                    'company_users' => 1,
                    'clients' => 2,
                    'document_requests' => 5,
                    'template_limit' => 2,
                    'request_templates' => 5,

                    'storage_mb' => 50,
                    'file_size_limit_mb' => 1,

                    'usage_reset_type' => 'monthly',

                    'allowed_mime_types' => [
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'application/zip',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ],

                    'allow_zip' => false,
                    'allow_video' => false,
                    'allow_multiple_uploads' => false,

                    'client_portal' => true,
                ],

                /*
                |--------------------------------------------------------------------------
                | STARTER PLAN
                |--------------------------------------------------------------------------
                */
                [
                    'slug' => 'starter',
                    'name' => 'Starter',

                    'monthly_price' => 9.00,
                    'yearly_price' => 90.00,
                    'currency' => 'USD',
                    'is_free' => false,

                    'is_popular' => false,
                    'sort_order' => 2,
                    'is_active' => true,

                    'stripe_product_id' => env('STRIPE_STARTER_PRODUCT'),
                    'stripe_price_monthly' => env('STRIPE_STARTER_MONTHLY'),
                    'stripe_price_yearly' => env('STRIPE_STARTER_YEARLY'),

                    'company_users' => 5,
                    'clients' => 100,
                    'document_requests' => 200,

                    'template_limit' => 10,
                    'request_templates' => 10,

                    'storage_mb' => 10240,
                    'file_size_limit_mb' => 20,
                    'whatsapp_limit' => 200,

                    'usage_reset_type' => 'monthly',

                    'allowed_mime_types' => [
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ],

                    'allow_zip' => false,
                    'allow_video' => false,
                    'allow_multiple_uploads' => false,

                    'client_portal' => true,
                    'export_excel' => true,
                ],

                /*
                |--------------------------------------------------------------------------
                | GROWTH PLAN (Most Popular)
                |--------------------------------------------------------------------------
                */
                [
                    'slug' => 'growth',
                    'name' => 'Growth',

                    'monthly_price' => 19.00,
                    'yearly_price' => 190.00,
                    'currency' => 'USD',
                    'is_free' => false,

                    'is_popular' => true,
                    'sort_order' => 3,
                    'is_active' => true,

                    'stripe_product_id' => env('STRIPE_GROWTH_PRODUCT'),
                    'stripe_price_monthly' => env('STRIPE_GROWTH_MONTHLY'),
                    'stripe_price_yearly' => env('STRIPE_GROWTH_YEARLY'),

                    'company_users' => 10,
                    'clients' => 1000,
                    'document_requests' => 2000,

                    'template_limit' => 25,
                    'request_templates' => 25,

                    'storage_mb' => 51200,
                    'file_size_limit_mb' => 50,
                    'whatsapp_limit' => 2000,

                    'usage_reset_type' => 'monthly',

                    'allowed_mime_types' => [
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'application/zip',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ],

                    'allow_zip' => true,
                    'allow_multiple_uploads' => true,

                    'client_portal' => true,
                    'otp_login' => true,
                    'approve_workflow' => true,
                    'download_zip' => true,
                    'expiry_tracking' => true,
                    'renewal_reminder' => true,
                    'scheduled_reminder' => true,
                    'export_excel' => true,
                    'branding' => true,
                ],

                /*
                |--------------------------------------------------------------------------
                | PRO PLAN
                |--------------------------------------------------------------------------
                */
                [
                    'slug' => 'pro',
                    'name' => 'Pro',

                    'monthly_price' => 39.00,
                    'yearly_price' => 390.00,
                    'currency' => 'USD',
                    'is_free' => false,

                    'is_popular' => false,
                    'sort_order' => 4,
                    'is_active' => true,

                    'stripe_product_id' => env('STRIPE_PRO_PRODUCT'),
                    'stripe_price_monthly' => env('STRIPE_PRO_MONTHLY'),
                    'stripe_price_yearly' => env('STRIPE_PRO_YEARLY'),

                    'company_users' => 20,
                    'clients' => 5000,
                    'document_requests' => 10000,

                    'template_limit' => 50,
                    'request_templates' => 50,

                    'storage_mb' => 102400,
                    'file_size_limit_mb' => 100,
                    'whatsapp_limit' => 5000,

                    // No wildcard for security
                    'allowed_mime_types' => [
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'application/zip',
                        'video/mp4',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ],

                    'allow_zip' => true,
                    'allow_video' => true,
                    'allow_multiple_uploads' => true,

                    'client_portal' => true,
                    'otp_login' => true,
                    'approve_workflow' => true,
                    'reupload_history' => true,
                    'download_zip' => true,
                    'expiry_tracking' => true,
                    'renewal_reminder' => true,
                    'scheduled_reminder' => true,
                    'escalation_reminder' => true,
                    'export_excel' => true,
                    'export_pdf' => true,
                    'branding' => true,
                    'white_label' => true,
                    'priority_support' => true,
                ],
            ];

            foreach ($plans as $plan) {
                Plan::updateOrCreate(
                    ['slug' => $plan['slug']],
                    $plan
                );
            }
        });

        Cache::forget('pricing_plans');
    }
}
