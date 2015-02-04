<?php

class EventLogTableSeeder extends Seeder {

    public function run()
    {
        DB::table('event_log') -> delete();

        EventLog::create(array('tag' => '30342848A80A5B0000000005', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 16:30:15', 'updated_at' => '2014-04-21 16:30:15'));
        EventLog::create(array('tag' => '30342848A80A5B0000000002', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 16:33:15', 'updated_at' => '2014-04-21 16:33:15'));
        EventLog::create(array('tag' => '30342848A80A5B0000000006', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 16:45:15', 'updated_at' => '2014-04-21 16:45:15'));
        EventLog::create(array('tag' => '30342848A80A5B0000000004', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 16:51:15', 'updated_at' => '2014-04-21 16:51:15'));
        EventLog::create(array('tag' => '30342848A80A5B0000000003', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 16:52:17', 'updated_at' => '2014-04-21 16:52:17'));
        EventLog::create(array('tag' => '30342848A80A5B0000000007', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 16:59:10', 'updated_at' => '2014-04-21 16:59:10'));
        EventLog::create(array('tag' => '30342848A80ABE8000000002', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 17:10:53', 'updated_at' => '2014-04-21 17:10:53'));
        EventLog::create(array('tag' => '30342848A80ABE4000000002', 'event_id' => 1, 'antenna_in' => '2', 'antenna_out' => '1', 'created_at' => '2014-04-21 17:15:10', 'updated_at' => '2014-04-21 17:15:10'));
        EventLog::create(array('tag' => '303424DEFC2E390000000009', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 17:18:25', 'updated_at' => '2014-04-21 17:18:25'));
        EventLog::create(array('tag' => '30342848A80ABEC000000002', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 17:45:32', 'updated_at' => '2014-04-21 17:45:32'));
        EventLog::create(array('tag' => '30342848A80ABEC000000003', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 17:45:32', 'updated_at' => '2014-04-21 17:45:32'));
        EventLog::create(array('tag' => '30342848A80ABE4000000003', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 19:53:08', 'updated_at' => '2014-04-21 19:53:08'));
        EventLog::create(array('tag' => '6DFF59486DFF59486DFF5948', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 20:01:45', 'updated_at' => '2014-04-21 20:01:45'));
        EventLog::create(array('tag' => '2F390F632F390F632F390F63', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 20:31:14', 'updated_at' => '2014-04-21 20:31:14'));
        EventLog::create(array('tag' => '6DFF59486DFF59486DFF5948', 'event_id' => 1, 'antenna_in' => '2', 'antenna_out' => '1', 'created_at' => '2014-04-21 20:56:12', 'updated_at' => '2014-04-21 20:56:12'));
        EventLog::create(array('tag' => '303424DEFC2E39000000000A', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 22:10:18', 'updated_at' => '2014-04-21 22:10:18'));
        EventLog::create(array('tag' => '303424DEFC2E39000000000B', 'event_id' => 1, 'antenna_in' => '1', 'antenna_out' => '2', 'created_at' => '2014-04-21 22:56:10', 'updated_at' => '2014-04-21 22:56:10'));
    }

}
