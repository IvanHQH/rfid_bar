<?php

class EventLog extends Eloquent {
    protected $table = 'event_log';

    public function tag() {
        return $this -> hasOne('TagMapping', 'tag', 'tag');
    }

    public function product() {
        return $this->hasManyThrough('Product', 'TagMapping');
    }
}
