<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGhpbContributor extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ghpb_contributor',function(Blueprint $table){

            $table->integer('id');
            $table->integer('ghuser_id');
            $table->string('username');

            //keys
            $table->primary('id');
            $table->index('ghuser_id');
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
        Schema::drop('ghpb_contributor');
    }


}
