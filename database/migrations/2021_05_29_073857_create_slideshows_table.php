<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlideshowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slideshows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('course_id');
            $table->string('title');
            $table->text('description');
            $table->text('cover_pic');


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
            $table->timestamps();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slideshows');
    }
}
