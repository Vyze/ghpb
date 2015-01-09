<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGhpbUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::create('ghpb_user',function(Blueprint $table){

            $table->integer('id');

            // main fields
            $table->string('username');
            $table->string('name');

            //keys
            $table->primary('id');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
        Schema::drop('ghpb_user');
	}

}
