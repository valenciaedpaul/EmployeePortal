<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeesTable extends Migration {

	public function up()
	{
		Schema::create('employees', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('first_name');
			$table->string('middle_name')->nullable();
			$table->string('last_name');
			$table->string('gender');
			$table->integer('type_id')->unsigned();
			$table->integer('department_id')->unsigned();
			$table->string('email')->unique();
			$table->string('password');
			$table->rememberToken();
			$table->tinyInteger('status');
			$table->integer('used_paid_leaves')->default('0');
			$table->integer('used_unpaid_leaves')->default('0');
			$table->string('profile_pic')->nullable()->default(NULL);
		});
	}

	public function down()
	{
		Schema::drop('employees');
	}
}