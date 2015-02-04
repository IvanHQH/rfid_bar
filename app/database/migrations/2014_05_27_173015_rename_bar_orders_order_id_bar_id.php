<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameBarOrdersOrderIdBarId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bar_orders', function(Blueprint $table)
		{
			$table -> renameColumn('order_id', 'bar_id');
			$table -> integer('event_id') -> unsigned() -> after('id');
            $table -> string('name', 150) -> after('event_id');
			$table -> dropColumn('upc');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bar_orders', function(Blueprint $table)
		{
            $table -> string('upc', 21) -> default('');
			$table -> dropColumn('name');
			$table -> dropColumn('event_id');
			$table -> renameColumn('bar_id', 'order_id');
		});
	}

}
