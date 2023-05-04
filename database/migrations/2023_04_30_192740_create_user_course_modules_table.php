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
        Schema::create('user_course_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("cmid");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("course_id");
            $table->unsignedFloat("grade");
            $table->string("name");


            $table->foreign("course_id")->references("course_id")->on("courses");
            $table->foreign("user_id")->references("user_id")->on("moodle_token_infos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_course_modules');
    }
};
