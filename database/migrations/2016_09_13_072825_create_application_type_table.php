<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplicationTypeTable extends Migration {

	public function up()
	{
		Schema::create('application_type', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('unit');
			$table->tinyInteger('paid')->default('1');
			$table->text('description');
		});
	}

	public function down()
	{
		Schema::drop('application_type');
	}
}