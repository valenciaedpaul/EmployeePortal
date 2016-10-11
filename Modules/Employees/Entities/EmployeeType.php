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

	public static function hasTopAccess($id)
	{
		$employee_type = self::find($id);
		if($employee_type){
			return $employee_type->access_level == 1;
		}else{
			return false;
		}
	}
}