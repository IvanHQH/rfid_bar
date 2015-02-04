<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventories', function($table){
            $table -> increments('id') -> unsigned();
            $table -> integer('event_id') -> unsigned();
            $table -> string('name', 150);
            $table -> integer('inventory_type') -> unsigned();
            $table -> integer('total_tags') -> unsigned() -> default(0);
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
		Schema::drop('inventories');
	}

}
