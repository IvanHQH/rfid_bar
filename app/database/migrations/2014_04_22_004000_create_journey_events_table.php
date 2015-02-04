<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJourneyEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('journey_events', function($table){
            $table -> increments('id') -> unsigned();
            $table -> string('event_name', 150);
            $table -> string('description', 255)-> nullable();
            $table -> dateTime('started_at');
            $table -> dateTime('finished_at') -> nullable();
            $table -> boolean('active') -> default(false);
            $table -> timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('journey_events');
	}

}
