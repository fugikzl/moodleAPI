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
        Schema::create('user_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("assignment_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedFloat("grade");

            $table->foreign("assignment_id")->references("assignment_id")->on("course_assignments");
            $table->foreign("user_id")->references("user_id")->on("moodle_token_infos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_assignments');
    }
};
