<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarOrdersSummary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bar_orders_summary', function(Blueprint $table)
		{
			$table->increments('id');
            $table -> integer('bar_order_id') -> unsigned();
            $table -> string('upc', 50);
            $table -> integer('total') -> unsigned();
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
		Schema::drop('bar_orders_summary');
	}

}
