<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	public function run()
	{
		Model::unguard();

		$this->call('EmployeeTableSeeder');
		$this->command->info('Department table seeded!');

		$this->call('EmployeeTypeTableSeeder');
		$this->command->info('EmployeeType table seeded!');

		$this->call('DepartmentTableSeeder');
		$this->command->info('Department table seeded!');

		$this->call('ApplicationTypeTableSeeder');
		$this->command->info('Department table seeded!');
	}
}