<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bars', function($table){
            $table -> increments('id') -> unsigned();
            $table -> string('name', 150);
            $table -> string('description', 255);
            $table -> timestamps();
            $table -> softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bars');
	}

}
