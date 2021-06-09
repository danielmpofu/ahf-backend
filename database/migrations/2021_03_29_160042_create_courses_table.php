<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {

            $table->id();
            $table->text('title');
            $table->text('description');
            $table->text('entry_requirements');
            $table->text('cover_image');
            $table->text('content');

            $table->string('optional')
                ->default('optional');

            $table->string('duration');
            $table->string('duration_units')
                ->default('weeks');

            $table->string('lectures')
                ->default('1');

            $table->string('quizzes')
                ->default('1');

            $table->string('pass')
                ->default('50');


            $table->integer('level')
                ->unsigned()
                ->index();

            $table->unsignedBigInteger('instructor_id');
            $table->timestamps();

            $table->foreign('instructor_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('level')
                ->references('id')
                ->on('levels')
                ->onDelete('cascade');

            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
