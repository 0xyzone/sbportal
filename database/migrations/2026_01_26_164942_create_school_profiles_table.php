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
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('image_path')->nullable();
            $table->string(column: 'phone_number')->nullable();
            $table->string(column: 'alternate_phone_number')->nullable();
            $table->longText(column: 'physical_address')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('representative_position')->nullable();
            $table->string('representative_phone')->nullable();
            $table->string('website_url')->nullable();
            $table->string('principle_contact_name')->nullable();
            $table->string('principle_contact_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};
