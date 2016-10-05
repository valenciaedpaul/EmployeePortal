<?php

use Illuminate\Database\Seeder;
use Modules\Employees\Entities\Department;

class DepartmentTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('departments')->delete();

		// departments_seeder
		Department::create(array(
				'name' => 'Development'
			));
		Department::create(array(
			'name' => 'Admin'
		));
	}
}