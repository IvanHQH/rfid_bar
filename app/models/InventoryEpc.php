<?php

class InventoryEpc extends \Eloquent {
	protected $fillable = [];
	protected $table = 'inventory_epcs';
	//protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	protected $softDelete = true;
	
	public function inventory() {
		return $this -> belongsTo('Inventory', 'inventory_id');
	}
}