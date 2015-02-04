<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bar_orders', function($table){
            $table -> increments('id') -> unsigned();
            $table -> integer('order_id') -> unsigned();
            $table -> string('upc', 21);
            $table -> integer('qty') -> unsigned();
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
		Schema::drop('bar_orders');
	}

}
