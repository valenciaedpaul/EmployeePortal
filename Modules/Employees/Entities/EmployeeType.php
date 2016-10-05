<?php

namespace Modules\Employees\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model {

	protected $table = 'employee_types';
	public $timestamps = false;

	public function scopeGetCanApprove($query)
	{
		return $query->where('can_approve', 1)->get();
	}

	public static function canApprove($type_id)
	{
		return self::find($type_id)->can_approve;
	}
}