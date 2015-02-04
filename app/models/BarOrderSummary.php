<?php

class BarOrderSummary extends \Eloquent {
	protected $fillable = [];
	protected $table = 'bar_orders_summary';
	//protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	protected $softDelete = true;
	
	public function bar_order() {
		return $this -> belongsTo('BarOrder', 'bar_order_id');
	}
	
	public function product() {
		return $this -> belongsTo('Product', 'upc', 'upc');
	}
}