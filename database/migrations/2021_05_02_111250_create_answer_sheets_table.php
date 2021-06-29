<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_sheets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('exercise_id');
            $table->unsignedBigInteger('question_id');
            $table->text('question');
            $table->text('answer');
            $table->text('correct');
            $table->text('provided_answer')->nullable(true);
            $table->text('answer_explanation')->nullable(true);
            $table->text('choice_one');
            $table->text('choice_two');
            $table->text('choice_three');
            $table->text('choice_four');
            $table->text('attachment_url')->nullable(true);

            $table->softDeletes();

            $table->foreign('question_id')
                ->references('id')
                ->on('mcqs')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('exercise_id')
                ->references('id')
                ->on('exercises')
                ->onDelete('cascade');

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreign('attempt_id')
                ->references('id')
                ->on('exercise_attempts')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_sheets');
    }
}
