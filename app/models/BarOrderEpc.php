<?php

class BarOrderEpc extends \Eloquent {
	protected $fillable = [];
	protected $table = 'bar_orders_epc';
	//protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	protected $softDelete = true;
	
	public function bar_order() {
		return $this -> belongsTo('BarOrder', 'bar_order_id');
	}
}