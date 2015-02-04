<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function($table){
            $table -> increments('id');
            $table -> string('upc', 100);
            $table -> string('product_name', 100);
            $table -> string('description', 255) -> default('');
            $table -> string('color', 7) -> default('#FFFFFF');
            $table -> timestamps();
            $table -> softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}
