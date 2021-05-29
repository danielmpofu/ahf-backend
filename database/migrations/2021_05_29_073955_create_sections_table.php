<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slideshow_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slideshow_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('created_by');

            $table->string('title');
            $table->text('description');
            $table->string('number');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('slideshow_id')
                ->references('id')
                ->on('slideshows')
                ->onDelete('cascade');

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

    public function down()
    {
        Schema::dropIfExists('sections');
    }
}
