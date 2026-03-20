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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();

            // Belongs to super admin user (one company per owner)
            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->unique();

            // Basic Company Info
            $table->string('company_name', 150);
            $table->string('company_logo', 255)->nullable();

            // Contact Info
            $table->string('email', 150)->nullable()->index();
            $table->string('phone', 50)->nullable();

            // Address Info
            $table->string('address_line_1', 150)->nullable();
            $table->string('address_line_2', 150)->nullable();
            $table->string('city', 100)->nullable()->index();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 80)->nullable()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
