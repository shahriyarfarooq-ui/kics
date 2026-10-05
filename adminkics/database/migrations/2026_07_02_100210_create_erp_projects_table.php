<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erp_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('kics_id')->unique();
            $table->unsignedInteger('campus_id')->nullable();
            $table->unsignedInteger('department_kics_id')->nullable();
            $table->unsignedBigInteger('erp_department_id')->nullable();
            $table->string('name')->nullable();
            $table->string('campus')->nullable();
            $table->string('project_manager')->nullable();
            $table->string('project_coordinator')->nullable();
            $table->string('project_sponser')->nullable();
            $table->string('customer')->nullable();
            $table->string('project_states')->nullable();
            $table->string('project_type')->nullable();
            $table->boolean('system_generated')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('erp_department_id')->references('id')->on('erp_department')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_projects');
    }
};
