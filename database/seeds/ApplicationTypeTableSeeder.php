<?php

use Illuminate\Database\Seeder;
use Modules\Applications\Entities\ApplicationType;

class ApplicationTypeTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('application_type')->delete();

		// ApplicationTypesSeeder
		ApplicationType::create(array(
				'name' => 'Vacation Leave',
				'unit' => 'days',
				'paid' => 1
			));

		// ApplicationTypesSeeder
		ApplicationType::create(array(
			'name' => 'Sick Leave',
			'unit' => 'days',
			'paid' => 0
		));

		// ApplicationTypesSeeder
		ApplicationType::create(array(
			'name' => 'Overtime',
			'unit' => 'hours',
			'paid' => 1
		));

		// ApplicationTypesSeeder
		ApplicationType::create(array(
			'name' => 'CWS',
			'unit' => 'hours',
			'paid' => 1,
			'description' => 'Change Work Schedule'
		));
	}
}