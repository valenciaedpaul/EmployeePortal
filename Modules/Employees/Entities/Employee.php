<?php

namespace Modules\Employees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Employee extends Authenticatable {

	protected $table = 'employees';
	public $timestamps = true;

	protected $hidden = [
		'password', 'remember_token',
	];

	public function scopeSupervisors($query, $employee)
	{
		$canApproveEmployeeTypes = EmployeeType::getCanApprove();
		$canApproveEmployeeTypeIDs = [];
		if($canApproveEmployeeTypes){
			foreach($canApproveEmployeeTypes as $c){
				$canApproveEmployeeTypeIDs[] = $c->id;
			}
		}
		return (count($canApproveEmployeeTypeIDs) > 0) ?
			$query->whereIn('type_id', $canApproveEmployeeTypeIDs)
				->where('department_id', $employee->department_id)
				->where('id', '<>', $employee->id)
				->get()
			: null;
	}

	public function scopeBelongsToDepartment($query, $employee_id)
	{
		$department_id = $query->find($employee_id)->department_id;
		return Department::find($department_id);
	}

	public static function canApprove($id = null)
	{
		$employee_id = $id ? $id : Auth::user()->id;
		$employee = self::find($employee_id);
		if($employee){
			return EmployeeType::canApprove($employee->type_id);
		}else{
			return false;
		}
	}

}