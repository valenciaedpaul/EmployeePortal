<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplicationsTable extends Migration {

	public function up()
	{
		Schema::create('applications', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('employee_id')->unsigned();
			$table->integer('application_type_id')->unsigned();
			$table->date('date_from');
			$table->date('date_to');
			$table->decimal('number_of_days')->nullable();
			$table->decimal('overtime_hours')->nullable();
			$table->integer('supervisor_id')->unsigned();
			$table->text('reason');
			$table->string('status')->default('pending');
			$table->text('remarks')->nullable();
			$table->integer('reviewer_id')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('applications');
	}
}