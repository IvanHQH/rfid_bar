<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventorySummaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventory_summary', function($table){
            $table -> increments('id') -> unsigned();
            $table -> integer('inventory_id') -> unsigned();
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
		Schema::drop('inventory_summary');
	}

}
