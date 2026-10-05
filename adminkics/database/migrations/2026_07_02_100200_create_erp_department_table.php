<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erp_department', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('kics_id')->unique();
            $table->unsignedInteger('campus_id')->nullable();
            $table->unsignedInteger('parent_department_id')->nullable();
            $table->string('name')->nullable();
            $table->string('complete_name')->nullable();
            $table->string('parent_department')->nullable();
            $table->string('manager')->nullable();
            $table->string('campus')->nullable();
            $table->boolean('active')->default(true);
            $table->string('dept_code')->nullable();
            $table->string('dept_type')->nullable();
            $table->string('department_type')->nullable();
            $table->text('web_department_name')->nullable();
            $table->longText('web_detail_description')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('appraisals_to_process')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_department');
    }
};
