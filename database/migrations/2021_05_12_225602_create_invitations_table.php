<?php

use App\Models\UserInvitation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();

            $table->string('key');
            $table->unsignedBigInteger('user_id') ->nullable(true);
            $table->unsignedBigInteger('invited_by');
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email_status');
            $table->string('status');
            $table->string('expiry') ->nullable(true);
            $table->string('invited_to')->default(UserInvitation::$inv_system);
            $table->string('revoked')->default('false');
            $table->text('message');
            $table->string('user_role')->default('1');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('invited_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
