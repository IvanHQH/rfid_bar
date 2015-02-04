<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('event_log', function($table){
            $table -> increments('id') -> unsigned();
            $table -> integer('event_id') -> unsigned();
            $table -> string('tag', 48);
            $table -> string('upc', 48) -> nullable() -> default(null);
            $table -> string('antenna_in', 45);
            $table -> string('antenna_out', 45);
            $table -> timestamps();
            $table -> foreign('event_id') -> references('id') -> on('journey_events');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('event_log');
	}

}
