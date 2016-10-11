<?php

namespace Modules\Applications\Entities;

use Illuminate\Database\Eloquent\Model;

class ApplicationType extends Model {

	protected $table = 'application_type';
	public $timestamps = false;

	const PAID = 1;
	const UNPAID = 0;
}