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
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->integer('group_id'); // Foreign Key
            $table->string('location')->nullable();
            $table->string('company')->nullable();
            $table->date('job_close_date')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();

            $table->foreign('group_id')
                ->references('group_id')
                ->on('kic_group')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('careers');
    }
};
