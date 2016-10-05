<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeeTypesTable extends Migration {

	public function up()
	{
		Schema::create('employee_types', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->tinyInteger('access_level');
			$table->integer('paid_leave_credits');
			$table->string('unpaid_leave_credits');
			$table->tinyInteger('can_approve')->default('0');
		});
	}

	public function down()
	{
		Schema::drop('employee_types');
	}
}