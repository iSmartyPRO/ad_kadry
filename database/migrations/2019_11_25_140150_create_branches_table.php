<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->text('shortcode');
            $table->text('name');
            $table->text('ad_dn');
            $table->text('city');
            $table->text('postalCode');
            $table->text('address');
            $table->text('mail_group1')->nullable();
            $table->text('mail_group2')->nullable();
            $table->text('mail_group3')->nullable();
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
        Schema::dropIfExists('branches');
    }
}
