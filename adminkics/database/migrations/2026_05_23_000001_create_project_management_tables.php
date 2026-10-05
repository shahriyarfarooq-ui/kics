<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kic_subgroup')) {
            Schema::create('kic_subgroup', function (Blueprint $table) {
                $table->integer('subgroup_id', true);
                $table->integer('group_id');
                $table->string('subgroup_name')->default('');
                $table->integer('subgroup_seqno')->default(0);
                $table->text('subgroup_description')->nullable();
                $table->string('subgroup_briefdescription')->default('');
                $table->integer('subgroup_projectlist_check')->default(0);
                $table->integer('subgroup_services_check')->default(0);
                $table->integer('subgroup_rdproject_check')->default(0);
            });
        }

        if (!Schema::hasTable('kic_rdproject')) {
            Schema::create('kic_rdproject', function (Blueprint $table) {
                $table->integer('rdproject_id', true);
                $table->string('rdproject_name')->default('');
                $table->string('rdproject_seqno')->default('');
                $table->text('rdproject_description')->nullable();
                $table->string('rdproject_small_picture')->default('');
                $table->integer('group_id')->default(0);
                $table->integer('subgroup_id')->default(0);
                $table->integer('sub_site_id')->default(0);
            });
        }

        if (!Schema::hasTable('project_activities')) {
            Schema::create('project_activities', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('task');
                $table->longText('description')->nullable();
                $table->date('start_date')->nullable();
                $table->date('expected_date')->nullable();
                $table->integer('project_id');

                $table->index('project_id');
                $table->foreign('project_id')->references('projectlist_id')->on('kic_group_projectlist')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }

        if (!Schema::hasTable('project_content')) {
            Schema::create('project_content', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('project_id');
                $table->longText('tpr')->nullable();
                $table->longText('team')->nullable();
                $table->longText('publications')->nullable();
                $table->longText('aad')->nullable();
                $table->longText('usecase')->nullable();
                $table->longText('downloads')->nullable();

                $table->index('project_id');
                $table->foreign('project_id')->references('projectlist_id')->on('kic_group_projectlist')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }

        if (!Schema::hasTable('project_downloads')) {
            Schema::create('project_downloads', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('title');
                $table->longText('description')->nullable();
                $table->string('file')->default('');
                $table->integer('project_id')->nullable();
                $table->string('url', 512)->default('');

                $table->index('project_id');
                $table->foreign('project_id')->references('projectlist_id')->on('kic_group_projectlist')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }

        if (!Schema::hasTable('project_phases')) {
            Schema::create('project_phases', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('phase');
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->date('extended_date')->nullable();
                $table->boolean('is_complete')->default(false);
                $table->integer('projectlist_id');

                $table->index('projectlist_id');
                $table->foreign('projectlist_id')->references('projectlist_id')->on('kic_group_projectlist')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }

        if (!Schema::hasTable('project_plan')) {
            Schema::create('project_plan', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('task', 256);
                $table->longText('description')->nullable();
                $table->string('assigned_to', 256)->default('');
                $table->date('start_date')->nullable();
                $table->string('expected', 256)->default('');
                $table->integer('project_id')->nullable();

                $table->index('project_id');
                $table->foreign('project_id')->references('projectlist_id')->on('kic_group_projectlist')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }

        if (!Schema::hasTable('project_publications')) {
            Schema::create('project_publications', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('project_id');
                $table->integer('publication_id');

                $table->index('project_id');
                $table->index('publication_id');
                $table->foreign('project_id')->references('projectlist_id')->on('kic_group_projectlist')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('publication_id')->references('publication_id')->on('kic_publications')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }

        if (!Schema::hasTable('project_team')) {
            Schema::create('project_team', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('project_id');
                $table->integer('member_id');
                $table->string('role')->default('');

                $table->index('project_id');
                $table->index('member_id');
                $table->foreign('project_id')->references('projectlist_id')->on('kic_group_projectlist')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('member_id')->references('people_id')->on('people')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_team');
        Schema::dropIfExists('project_publications');
        Schema::dropIfExists('project_plan');
        Schema::dropIfExists('project_phases');
        Schema::dropIfExists('project_downloads');
        Schema::dropIfExists('project_content');
        Schema::dropIfExists('project_activities');
        Schema::dropIfExists('kic_rdproject');
        Schema::dropIfExists('kic_subgroup');
    }
};
