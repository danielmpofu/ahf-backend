<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMcqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mcqs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('exercise_id');

            $table->text('question');
            $table->text('answer');
            $table->text('provided_answer')->nullable(true);
            $table->text('answer_explanation')->nullable(true);

            $table->text('choice_one');
            $table->text('choice_two');
            $table->text('choice_three');
            $table->text('choice_four');
            $table->text('attachment_url')->nullable(true);

            $table->softDeletes();

            $table->foreign('created_by')
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
        Schema::dropIfExists('mcqs');
    }
}
