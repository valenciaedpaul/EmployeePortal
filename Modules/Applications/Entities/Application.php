<?php

namespace Modules\Applications\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Application extends Model {

	protected $table = 'applications';
	public $timestamps = true;

	const APPROVED = 'approved';
	const DENIED = 'denied';
	const PENDING = 'pending';

	public function scopeEmployeeApplications($query, $employee_id)
	{
		if($employee_id !== null && $employee_id !== ''){
			return $query->where('employee_id', $employee_id)->get();
		}
		return false;
	}

	public function scopeApprove($query, $id)
	{
		$application = $query->find($id);
		$application->status = self::APPROVED;
		$application->reviewer_id = Auth::user()->id;
		return $application->save();
	}

	public function scopeDeny($query, $id)
	{
		$application = $query->find($id);
		$application->status = self::DENIED;
		$application->reviewer_id = Auth::user()->id;
		return $application->save();
	}
}