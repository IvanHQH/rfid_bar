<?php

class JourneyEventsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('journey_events') -> delete();

        JourneyEvent::create(array('event_name' => 'Test Event', 'description' => 'Event Name', 'started_at' => '2014-04-21 12:00:00', 'finished_at' => null, 'active' => true));
    }
}
