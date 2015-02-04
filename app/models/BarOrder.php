<?php

class BarOrder extends \Eloquent {
	protected $fillable = [];
	protected $table = 'bar_orders';
	//protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	protected $softDelete = true;
	
	public function epcs() {
		return $this -> hasMany('BarOrderEpc', 'bar_order_id');
	}
	
	public function summaries() {
		return $this -> hasMany('BarOrderSummary', 'bar_order_id');
	}
	
	public function upcs() {
		return $this -> summaries();
	}
	
	public function journeyevent() {
		return $this -> belongsTo('JourneyEvent', 'event_id');
	}
	
	public function event() {
		return $this -> journeyevent();
	}
}