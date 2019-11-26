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
            $table->string('adLocation');
            $table->string('last_name_rus');
            $table->string('first_name_rus');
            $table->string('last_name_eng');
            $table->string('first_name_eng');
            $table->string('department');
            $table->string('position');
            $table->string('status');
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
