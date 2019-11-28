<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('telegram_id')->nullable();
            $table->string('user_type')->nullable();
            $table->string('nameRus')->nullable();
            $table->string('nameEng')->nullable();
            $table->string('adLocation')->nullable();
            $table->string('location')->nullable();
            $table->string('postalCode')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('mobile')->nullable();
            $table->string('extention')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('company')->nullable();
            $table->string('cn')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('users');
    }
}
