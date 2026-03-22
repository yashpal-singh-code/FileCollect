<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $commonFeatures = [
                'allow_zip' => true,
                'client_portal' => true,
                'mfa_authentication' => true,
                'download_zip' => true,
                'expiry_tracking' => true,
                'branding' => true,
                'white_label' => true,
                'priority_support' => true,
            ];

            $plans = [

                [
                    'slug' => 'free',
                    'name' => 'Free',
                    'monthly_price' => 0,
                    'yearly_price' => 0,
                    'currency' => 'USD',
                    'is_free' => true,
                    'is_popular' => false,
                    'is_active' => true,
                    'sort_order' => 1,

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
                ] + $commonFeatures,

                [
                    'slug' => 'starter',
                    'name' => 'Starter',
                    'monthly_price' => 19,
                    'yearly_price' => 160,
                    'currency' => 'USD',
                    'is_free' => false,
                    'is_popular' => false,
                    'is_active' => true,
                    'sort_order' => 2,

                    'razorpay_plan_monthly' => env('RAZORPAY_STARTER_MONTHLY'),
                    'razorpay_plan_yearly' => env('RAZORPAY_STARTER_YEARLY'),

                    'company_users' => 2,
                    'clients' => 50,
                    'document_requests' => 150,
                    'template_limit' => 5,
                    'request_templates' => 10,

                    'storage_mb' => 10240,
                    'file_size_limit_mb' => 5,

                    'usage_reset_type' => 'monthly',

                    'allowed_mime_types' => [
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'application/zip',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ],
                ] + $commonFeatures,

                [
                    'slug' => 'growth',
                    'name' => 'Growth',
                    'monthly_price' => 49,
                    'yearly_price' => 412,
                    'currency' => 'USD',
                    'is_free' => false,
                    'is_popular' => true,
                    'is_active' => true,
                    'sort_order' => 3,

                    'razorpay_plan_monthly' => env('RAZORPAY_GROWTH_MONTHLY'),
                    'razorpay_plan_yearly' => env('RAZORPAY_GROWTH_YEARLY'),

                    'company_users' => 10,
                    'clients' => 100,
                    'document_requests' => 500,
                    'template_limit' => 20,
                    'request_templates' => 25,

                    'storage_mb' => 51200,
                    'file_size_limit_mb' => 20,

                    'usage_reset_type' => 'monthly',

                    'allowed_mime_types' => [
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'application/zip',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ],
                ] + $commonFeatures,

                [
                    'slug' => 'pro',
                    'name' => 'Pro',
                    'monthly_price' => 99,
                    'yearly_price' => 832,
                    'currency' => 'USD',
                    'is_free' => false,
                    'is_popular' => false,
                    'is_active' => true,
                    'sort_order' => 4,

                    'razorpay_plan_monthly' => env('RAZORPAY_PRO_MONTHLY'),
                    'razorpay_plan_yearly' => env('RAZORPAY_PRO_YEARLY'),

                    'company_users' => 25,
                    'clients' => 500,
                    'document_requests' => 1000,
                    'template_limit' => 50,
                    'request_templates' => 50,

                    'storage_mb' => 102400,
                    'file_size_limit_mb' => 50,

                    'usage_reset_type' => 'monthly',

                    'allowed_mime_types' => [
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'application/zip',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ],
                ] + $commonFeatures,

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
