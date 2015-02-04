<?php

    class Bar extends \Eloquent {
		protected $fillable = array('name', 'description');
        protected $table = 'bars';
        //protected $hidden = array('description', 'color', 'created_at', 'updated_at', 'deleted_at');
        protected $softDelete = true;
    }