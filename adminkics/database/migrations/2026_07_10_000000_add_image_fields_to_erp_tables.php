<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add logo to erp_department
        Schema::table('erp_department', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('mission');
            $table->string('cover_image')->nullable()->after('logo');
            $table->boolean('is_visible')->default(true)->after('active');
            $table->text('admin_notes')->nullable()->after('is_visible');
        });

        // Add image to erp_projects
        Schema::table('erp_projects', function (Blueprint $table) {
            $table->string('image')->nullable()->after('project_type');
            $table->text('description')->nullable()->after('image');
            $table->boolean('is_visible')->default(true)->after('active');
            $table->text('admin_notes')->nullable()->after('is_visible');
        });
    }

    public function down(): void
    {
        Schema::table('erp_department', function (Blueprint $table) {
            $table->dropColumn(['logo', 'cover_image', 'is_visible', 'admin_notes']);
        });

        Schema::table('erp_projects', function (Blueprint $table) {
            $table->dropColumn(['image', 'description', 'is_visible', 'admin_notes']);
        });
    }
};
