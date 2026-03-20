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
        Schema::create('document_uploads', function (Blueprint $table) {

            $table->id();

            // Parent request
            $table->foreignId('document_request_id')
                ->constrained()
                ->cascadeOnDelete();

            // Tenant isolation (must match document_requests.owner_id)
            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Who uploaded (client or team member)
            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('field_label');

            $table->string('file_path');

            // Storage disk (private by default)
            $table->string('disk')->default('private');

            $table->string('original_name');

            $table->string('mime_type')->nullable();

            $table->string('file_extension', 20)->nullable();

            // SHA-256 file hash
            $table->string('file_hash', 64)->nullable()->index();

            $table->unsignedBigInteger('file_size')->default(0);

            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('uploaded_at')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            // Performance indexes
            $table->index(['owner_id', 'created_at']);
            $table->index(['owner_id', 'file_size']);
            $table->index(['document_request_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_uploads');
    }
};