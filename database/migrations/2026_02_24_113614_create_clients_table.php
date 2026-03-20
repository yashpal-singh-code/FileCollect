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
        Schema::create('clients', function (Blueprint $table) {

            $table->id();

            // Public UUID
            $table->uuid('uuid')->unique()->index();

            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();

            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();

            $table->string('full_name')->storedAs("CONCAT(first_name,' ',COALESCE(last_name,''))")->index();

            $table->string('email', 150)->nullable();
            $table->string('phone', 25)->nullable()->index();
            $table->string('company_name', 150)->nullable()->index();
            $table->string('company_logo', 255)->nullable();
            $table->string('password')->nullable();
            $table->timestamp('portal_password_set_at')->nullable();

            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();

            $table->boolean('portal_enabled')->default(false)->index();

            $table->string('portal_invite_token', 120)->nullable()->unique();

            $table->timestamp('portal_invited_at')->nullable();

            $table->timestamp('portal_last_login_at')->nullable()->index();

            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active')->index();

            $table->timestamp('last_activity_at')->nullable()->index();

            $table->string('address_line_1', 150)->nullable();
            $table->string('address_line_2', 150)->nullable();
            $table->string('city', 100)->nullable()->index();
            $table->string('state', 100)->nullable()->index();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 80)->nullable()->index();

            $table->longText('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Prevent duplicate emails inside same account
            $table->unique(['owner_id', 'email']);

            // Fast filtering
            $table->index(['owner_id', 'status']);
            $table->index(['owner_id', 'portal_enabled']);
            $table->index(['owner_id', 'full_name']);
            $table->index(['owner_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};