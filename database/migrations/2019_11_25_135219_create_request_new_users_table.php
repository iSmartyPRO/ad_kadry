<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestNewUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_new_users', function (Blueprint $table) {
            $table->increments('id');
            $table->text('adLocation');
            $table->text('last_name_rus');
            $table->text('first_name_rus');
            $table->text('last_name_eng');
            $table->text('first_name_eng');
            $table->text('department');
            $table->text('position');
            $table->text('status');
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
        Schema::dropIfExists('request_new_users');
    }
}
