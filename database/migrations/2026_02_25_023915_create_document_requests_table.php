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
        Schema::create('document_requests', function (Blueprint $table) {

            $table->id(); // Primary key

            // Tenant / Account Owner (Multi-tenant isolation)
            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete(); // Delete requests if owner deleted

            // Public identifier (Safe for URLs)
            $table->uuid('uuid')->unique()->index();

            // Per-owner sequential request number (DR-0001 etc.)
            $table->string('request_number');

            // Creator (team member who created request)
            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete(); // Keep request even if user deleted

            // Linked client
            $table->foreignId('client_id')
                ->constrained()
                ->cascadeOnDelete();

            // Template reference (by UUID)
            $table->uuid('template_uuid')->index();

            $table->foreign('template_uuid')
                ->references('uuid')
                ->on('templates')
                ->cascadeOnDelete();

            // Snapshot of template fields at creation time
            $table->json('fields');

            // Request status
            $table->enum('status', [
                'draft',
                'sent',
                'viewed',
                'in_progress',
                'completed',
                'expired',
                'cancelled'
            ])->default('draft')->index();

            // Activity timestamps
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('last_activity_at')->nullable()->index();

            // WhatsApp usage tracking (for monthly plan limits)
            $table->timestamp('whatsapp_sent_at')->nullable()->index();

            // Secure portal access
            $table->string('access_token', 64)->unique();
            $table->text('portal_url')->nullable();

            // Storage disk (S3 private/public etc.)
            $table->string('disk')->default('private');

            // Counters
            $table->unsignedInteger('upload_count')->default(0);
            $table->unsignedInteger('total_fields')->default(0);

            // Total uploaded file size in bytes (for storage limit enforcement)
            $table->unsignedBigInteger('total_upload_size')->default(0);

            // Optional message to client
            $table->text('message')->nullable();

            // Audit tracking
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Unique request number per owner
            $table->unique(['owner_id', 'request_number']);

            // Performance indexes
            $table->index(['owner_id', 'status']);
            $table->index(['owner_id', 'created_at']);
            $table->index(['owner_id', 'expires_at']);
            $table->index(['owner_id', 'last_activity_at']);
            $table->index(['client_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};