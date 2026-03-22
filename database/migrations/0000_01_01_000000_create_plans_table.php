<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {

            $table->id();

            // 🔹 Basic Info
            $table->string('name', 150);
            $table->string('slug', 100)->unique();

            // 💰 Pricing
            $table->decimal('monthly_price', 10, 2)->unsigned()->nullable();
            $table->decimal('yearly_price', 10, 2)->unsigned()->nullable();
            $table->enum('currency', ['INR', 'USD'])->default('INR');
            $table->boolean('is_free')->default(false);

            // ⭐ Plan State
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();

            // 🔐 Razorpay Integration
            $table->string('razorpay_plan_monthly')->nullable();
            $table->string('razorpay_plan_yearly')->nullable();

            $table->index('razorpay_plan_monthly');
            $table->index('razorpay_plan_yearly');

            // 👥 Core Limits
            $table->unsignedInteger('company_users')->nullable();
            $table->unsignedInteger('clients')->nullable();
            $table->unsignedInteger('document_requests')->nullable();
            $table->unsignedInteger('template_limit')->nullable();
            $table->unsignedInteger('request_templates')->nullable();

            // 💾 Storage
            $table->unsignedInteger('storage_mb')->default(100);
            $table->unsignedInteger('file_size_limit_mb')->default(10);

            // 🔁 Usage Reset
            $table->enum('usage_reset_type', ['monthly', 'none'])->default('monthly');

            // 📂 Allowed MIME types
            $table->json('allowed_mime_types')->nullable();

            // 📦 Upload Features
            $table->boolean('allow_zip')->default(false);

            // 🚀 Feature Flags
            $table->boolean('client_portal')->default(false);
            $table->boolean('mfa_authentication')->default(false);
            $table->boolean('download_zip')->default(false);
            $table->boolean('expiry_tracking')->default(false);
            $table->boolean('branding')->default(false);
            $table->boolean('white_label')->default(false);
            $table->boolean('priority_support')->default(false);

            $table->timestamps();

            // ⚡ Performance Index
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
