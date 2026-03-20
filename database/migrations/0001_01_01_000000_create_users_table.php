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
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            // Is this account owner?
            $table->boolean('is_owner')->default(false);
            $table->boolean('saas_owner')->default(false);

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('avatar')->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('job_title')->nullable();

            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete()->index();

            // monthly / yearly
            $table->string('billing_cycle')->nullable();

            $table->unsignedBigInteger('storage_used')->default(0)->comment('Stored in BYTES');

            // If this user was created by another owner
            $table->foreignId('created_by')->nullable()->index();

            $table->boolean('is_active')->default(true);

            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->timestamp('last_activity_at')->nullable();

            $table->timestamp('terms_accepted_at')->nullable();

            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['created_by', 'is_active']);
        });


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
