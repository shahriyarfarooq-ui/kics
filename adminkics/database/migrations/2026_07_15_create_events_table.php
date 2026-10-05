<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Date & Time
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->dateTime('registration_deadline')->nullable();
            
            // Location
            $table->string('location')->nullable();
            $table->text('address')->nullable();
            
            // Event Type & Status
            $table->enum('event_type', ['conference', 'workshop', 'seminar', 'summit', 'training', 'social', 'other'])->default('other');
            $table->enum('event_status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            
            // Images
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            
            // Flags
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_visible')->default(true);
            
            // Registration
            $table->string('registration_link')->nullable();
            
            // Speakers & Organizer
            $table->json('speakers')->nullable();
            $table->string('organizer')->nullable();
            
            // Contact
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};