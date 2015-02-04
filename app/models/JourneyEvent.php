<?php

class JourneyEvent extends \Eloquent {

	protected $fillable = array('event_name', 'description', 'active', 'started_at');
	protected $table = 'journey_events';
	//protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	protected $softDelete = true;
	
    public function inventories() {
		return $this -> hasMany('Inventory', 'event_id') -> orderBy('inventory_type') -> orderBy('created_at');
	}
	
	public function scopeActive($query)
    {
        return $query->whereActive(1)->first();
    }
	
}
