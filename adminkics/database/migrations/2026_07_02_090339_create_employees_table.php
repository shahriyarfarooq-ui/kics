<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('emp_id')->unique(); // <id> from API
        $table->unsignedInteger('campus_id')->nullable();
        $table->unsignedInteger('department_id')->nullable();
        $table->string('name')->nullable();
        $table->string('complete_name')->nullable();
        $table->string('prefix')->nullable();
        $table->string('father_name')->nullable();
        $table->string('job_title')->nullable();
        $table->string('department')->nullable();
        $table->string('campus')->nullable();
        $table->string('manager')->nullable();
        $table->string('coach')->nullable();
        $table->string('work_phone')->nullable();
        $table->string('work_email')->nullable();
        $table->string('mobile_phone')->nullable();
        $table->string('state')->nullable();
        $table->boolean('active')->default(true);
        $table->boolean('is_active')->default(true);
        $table->date('joining_date')->nullable();
        $table->decimal('experience', 8, 2)->default(0);
        $table->string('type')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
