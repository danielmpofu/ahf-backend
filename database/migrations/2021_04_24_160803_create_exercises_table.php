<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('course_id');

            $table->unsignedBigInteger('student_id');

            $table->unsignedBigInteger('created_by');

            $table->text('title');
            $table->string('attempts');
            $table->string('pass_mark');
            $table->string('duration');
            $table->string('start_time')->default('--');
            $table->string('end_time')->default('--');;
            $table->text('description');
            $table->string('unlocked')->default('false');
            $table->string('contribution');
            $table->string('final_test')->default('false');
            $table->text('requirements');

            $table->string('visibility')
                ->default('1')
                ->nullable(false);

            $table->string('preview_time')
                ->nullable(false)
                ->default('2');

            $table->string('order_position')
                ->nullable(false)
                ->default('0');

            $table->softDeletes();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('exercises');
    }
}
