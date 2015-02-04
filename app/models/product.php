<?php

    class Product extends Eloquent {

        protected $table = 'products';
        //protected $hidden = array('description', 'color', 'created_at', 'updated_at', 'deleted_at');
        protected $softDelete = true;

        public function tags() {
            return $this -> belongsToMany('TagMapping');
        }
        
        public function family() {
            return $this -> belongsTo('Family','family_id');
        }
    }
