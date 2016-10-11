<?php

use Illuminate\Database\Seeder;
use Modules\Employees\Entities\Employee;

class EmployeeTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('employees')->delete();

		// test_regular_employee_seeder
		Employee::create(array(
				'first_name' => 'Juan',
				'middle_name' => 'Tamad',
				'last_name' => 'Dela Cruz',
				'gender' => 'male',
				'type_id' => 1,
				'department_id' => 1,
				'email' => 'test@mailinator.com',
				'password' => Hash::make('12345678'),
				'status' => 1
			));

		// test_regular_employee_seeder
		Employee::create(array(
			'first_name' => 'Test One',
			'middle_name' => 'Can',
			'last_name' => 'Approve',
			'gender' => 'female',
			'type_id' => 2,
			'department_id' => 1,
			'email' => 'test_002@mailinator.com',
			'password' => Hash::make('12345678'),
			'status' => 1
		));

		// test_regular_employee_seeder
		Employee::create(array(
			'first_name' => 'Second',
			'middle_name' => 'Test',
			'last_name' => 'Can Approve',
			'gender' => 'male',
			'type_id' => 4,
			'department_id' => 1,
			'email' => 'test_003@mailinator.com',
			'password' => Hash::make('12345678'),
			'status' => 1
		));

		// test_regular_employee_seeder
		Employee::create(array(
			'first_name' => 'Third',
			'middle_name' => 'Test',
			'last_name' => 'Can Approve',
			'gender' => 'female',
			'type_id' => 2,
			'department_id' => 2,
			'email' => 'test_004@mailinator.com',
			'password' => Hash::make('12345678'),
			'status' => 1
		));
	}
}