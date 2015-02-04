<?php

class InventorySummary extends \Eloquent {
	protected $fillable = [];
	protected $table = 'inventory_summary';
	//protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	protected $softDelete = true;
	
	public function inventory() {
		return $this -> belongsTo('Inventory', 'inventory_id');
	}
	
	public function product() {
		return $this -> belongsTo('Product', 'upc', 'upc');
	}
}