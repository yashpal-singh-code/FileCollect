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
        Schema::create('document_request_events', function (Blueprint $table) {

            $table->id();

            // Parent request
            $table->foreignId('document_request_id')
                ->constrained()
                ->cascadeOnDelete();

            // Tenant isolation (critical for SaaS safety)
            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Event type
            $table->string('event', 50)->index();
            // draft, sent, viewed, uploaded, completed, reminder_sent, expired, cancelled

            // Optional metadata (IP, browser, file_id, etc.)
            $table->json('meta')->nullable();

            $table->timestamp('created_at')->useCurrent();

            // Performance indexes
            $table->index(['owner_id', 'created_at']);
            $table->index(['document_request_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_request_events');
    }
};