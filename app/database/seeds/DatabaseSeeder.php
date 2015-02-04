<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this -> call('UsersTypesTableSeeder');
		$this -> call('UsersTableSeeder');
		$this -> call('ProductsTableSeeder');
		$this -> call('TagsMappingsTableSeeder');
		$this -> call('JourneyEventsTableSeeder');
		$this -> call('EventLogTableSeeder');
	}

}
