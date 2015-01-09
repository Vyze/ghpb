<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGhpbRepo extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ghpb_repo',function(Blueprint $table){

            $table->increments('id');

            $table->integer('ghuser_id');
            $table->string('name');
            $table->string('owner');

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
        Schema::drop('ghpb_repo');
    }

}
