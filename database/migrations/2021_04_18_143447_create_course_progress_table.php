<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseProgressTable extends Migration
{
    public function up()
    {
        Schema::create('course_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('user_id');
            $table->text('resource_type');
            $table->unsignedInteger('points');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreign('resource_id')
                ->references('id')
                ->on('course_resources')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_progress');
    }
}
