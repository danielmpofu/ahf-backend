<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('title')->nullable();
            $table->string('user_type');
            $table->text('address')->nullable();
            $table->text('bio')->nullable();
            $table->text('dob')->nullable();
            $table->text('pic_url')->nullable();
            $table->string('country')->nullable();
            $table->string('verified')->nullable();
            $table->string('gender')->nullable();
            $table->string('active')->nullable();
            $table->string('admin')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
