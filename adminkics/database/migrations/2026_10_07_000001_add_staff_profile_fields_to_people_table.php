<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('people')) {
            return;
        }

        Schema::table('people', function (Blueprint $table) {
            foreach ([
                'about_me', 'education', 'achievements', 'certifications',
                'publications', 'work_experience', 'projects',
            ] as $column) {
                if (! Schema::hasColumn('people', $column)) {
                    $table->longText($column)->nullable();
                }
            }

            foreach (['profile_photo_path', 'linkedin_url', 'github_url', 'website_url'] as $column) {
                if (! Schema::hasColumn('people', $column)) {
                    $table->string($column)->nullable();
                }
            }

            if (! Schema::hasColumn('people', 'profile_visible')) {
                $table->boolean('profile_visible')->default(true);
            }
            if (! Schema::hasColumn('people', 'profile_edit_locked')) {
                $table->boolean('profile_edit_locked')->default(false);
            }
            if (! Schema::hasColumn('people', 'profile_updated_at')) {
                $table->timestamp('profile_updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('people')) {
            return;
        }

        Schema::table('people', function (Blueprint $table) {
            foreach ([
                'about_me', 'education', 'achievements', 'certifications',
                'publications', 'work_experience', 'projects', 'profile_photo_path',
                'linkedin_url', 'github_url', 'website_url', 'profile_visible',
                'profile_edit_locked', 'profile_updated_at',
            ] as $column) {
                if (Schema::hasColumn('people', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};