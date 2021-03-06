<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseResourcesTable extends Migration
{
    public function up()
    {
        Schema::create('course_resources', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('created_by');

            $table->unsignedBigInteger('points');

            $table->string('title');

            $table->string('visibility')
                ->default('1')
                ->nullable(false);

            $table->string('preview_time')
                ->nullable(false)
                ->default('2');

            $table->string('order_position')
                ->nullable(false)
                ->default('0');

            $table->text('description');
            $table->text('path')->nullable(true);;
            $table->text('file_type')->nullable(true);;
            $table->text('file_extension')->nullable(true);;
            $table->text('file_size')->nullable(true);
            $table->text('study_length')->nullable(true);




            $table->softDeletes();

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');;

        });
    }

    public function down()
    {
        Schema::dropIfExists('course_resources');
    }

}
