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
        Schema::create('templates', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('name', 150);
            $table->text('description')->nullable();

            $table->json('fields');

            $table->json('settings')->nullable();

            $table->unsignedSmallInteger('default_due_days')->nullable();

            $table->unsignedInteger('version')->default(1);

            $table->boolean('is_locked')->default(false)->index();

            $table->boolean('is_active')->default(true)->index();

            $table->unsignedInteger('usage_count')->default(0);

            $table->timestamp('last_used_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Performance indexes
            $table->index(['owner_id', 'created_at']);
            $table->index(['owner_id', 'is_active']);

            // Prevent duplicate template names per user
            $table->unique(['owner_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
