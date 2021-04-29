<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelsTable extends Migration
{

    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
          //  $table->id();
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->softDeletes();
            $table->timestamps();

//            $table->foreign('course_id')
//                ->references('id')
//                ->on('courses')
//                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('levels');
    }
}
