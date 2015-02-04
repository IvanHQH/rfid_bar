<?php
class Family extends Eloquent {
        protected $table = 'family';
        protected $softDelete = true;
        
        public function family() {
            return $this -> hasMany('Product');
        }
    }
