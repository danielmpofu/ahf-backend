<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExerciseTakesTable extends Migration
{
    public function up()
    {
        Schema::create('exercise_attempts', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('exercise_id');
            $table->unsignedBigInteger('score');
            $table->unsignedBigInteger('questions');
            $table->unsignedBigInteger('pass_mark');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreign('exercise_id')
                ->references('id')
                ->on('exercises')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exercise_takes');
    }
}
