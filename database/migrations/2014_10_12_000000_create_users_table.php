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
            $table->text('telegram_id')->nullable();
            $table->string('user_type')->nullable();
            $table->text('nameRus')->nullable();
            $table->text('nameEng')->nullable();
            $table->text('adLocation')->nullable();
            $table->text('location')->nullable();
            $table->text('postalCode')->nullable();
            $table->text('address')->nullable();
            $table->text('city')->nullable();
            $table->text('mobile')->nullable();
            $table->text('extention')->nullable();
            $table->text('position')->nullable();
            $table->text('department')->nullable();
            $table->text('company')->nullable();
            $table->text('cn')->nullable();
            $table->text('email')->nullable();
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
