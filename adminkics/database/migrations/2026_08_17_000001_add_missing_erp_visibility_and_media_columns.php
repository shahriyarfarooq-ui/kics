<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('erp_department')) {
            return;
        }

        Schema::table('erp_department', function (Blueprint $table) {
            if (!Schema::hasColumn('erp_department', 'logo')) {
                $table->string('logo')->nullable()->after('mission');
            }

            if (!Schema::hasColumn('erp_department', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('logo');
            }

            if (!Schema::hasColumn('erp_department', 'is_visible')) {
                $table->boolean('is_visible')->default(true)->after('active');
            }

            if (!Schema::hasColumn('erp_department', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('is_visible');
            }
        });

        if (!Schema::hasTable('erp_projects')) {
            return;
        }

        Schema::table('erp_projects', function (Blueprint $table) {
            if (!Schema::hasColumn('erp_projects', 'image')) {
                $table->string('image')->nullable()->after('project_type');
            }

            if (!Schema::hasColumn('erp_projects', 'description')) {
                $table->text('description')->nullable()->after('image');
            }

            if (!Schema::hasColumn('erp_projects', 'is_visible')) {
                $table->boolean('is_visible')->default(true)->after('active');
            }

            if (!Schema::hasColumn('erp_projects', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('is_visible');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('erp_department')) {
            Schema::table('erp_department', function (Blueprint $table) {
                if (Schema::hasColumn('erp_department', 'logo')) {
                    $table->dropColumn('logo');
                }

                if (Schema::hasColumn('erp_department', 'cover_image')) {
                    $table->dropColumn('cover_image');
                }

                if (Schema::hasColumn('erp_department', 'is_visible')) {
                    $table->dropColumn('is_visible');
                }

                if (Schema::hasColumn('erp_department', 'admin_notes')) {
                    $table->dropColumn('admin_notes');
                }
            });
        }

        if (Schema::hasTable('erp_projects')) {
            Schema::table('erp_projects', function (Blueprint $table) {
                if (Schema::hasColumn('erp_projects', 'image')) {
                    $table->dropColumn('image');
                }

                if (Schema::hasColumn('erp_projects', 'description')) {
                    $table->dropColumn('description');
                }

                if (Schema::hasColumn('erp_projects', 'is_visible')) {
                    $table->dropColumn('is_visible');
                }

                if (Schema::hasColumn('erp_projects', 'admin_notes')) {
                    $table->dropColumn('admin_notes');
                }
            });
        }
    }
};