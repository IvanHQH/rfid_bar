<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarOrdersEpcUpcColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bar_orders_epc', function(Blueprint $table)
		{
			$table -> string('upc', 50) -> after('epc') -> default('');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bar_orders_epc', function(Blueprint $table)
		{
			$table -> dropColumn('upc');
		});
	}

}
