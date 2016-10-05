<?php

use Illuminate\Database\Seeder;
use Modules\Employees\Entities\EmployeeType;

class EmployeeTypeTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('employee_types')->delete();

		// employee_types_seeder
		EmployeeType::create(array(
				'name' => 'Regular Employee',
				'access_level' => 3,
				'paid_leave_credits' => 12,
				'unpaid_leave_credits' => 100,
				'can_approve' => 0,
			));
		EmployeeType::create(array(
			'name' => 'Supervisor',
			'access_level' => 2,
			'paid_leave_credits' => 12,
			'unpaid_leave_credits' => 100,
			'can_approve' => 1,
		));
		EmployeeType::create(array(
			'name' => 'Admin',
			'access_level' => 1,
			'paid_leave_credits' => 12,
			'unpaid_leave_credits' => 100,
			'can_approve' => 0
		));
		EmployeeType::create(array(
			'name' => 'Manager',
			'access_level' => 1,
			'paid_leave_credits' => 12,
			'unpaid_leave_credits' => 100,
			'can_approve' => 1,
		));
	}
}