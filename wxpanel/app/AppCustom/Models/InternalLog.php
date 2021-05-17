<?php

namespace App\AppCustom\Models;

use Illuminate\Http\Request;
use Sentinel;

class InternalLog extends ModelCustomBase
{
	
	protected $table = 'internallog';
	
	protected $guarded = [];
	
	public $customProperyNotLog = true;
	
	public static function customMethodGetDefaultDataLog($model)
	{
		
		$request = Request::capture();
		
		return [
	        'user_id' => Sentinel::check() ? Sentinel::getUser()->id : 0,
	        'user_name' => Sentinel::check() ? Sentinel::getUser()->first_name . ', ' . Sentinel::getUser()->last_name : '',
	        'url' => $request->url(),
			'http_method' => $request->method(),
	        'user_agent' => $request->header('User-Agent'),
	        'ip' => $request->ip(),
			'model_name' => \get_class($model),
			'model_id' => $model->customMethodGetId(),
			'model_table' => $model->getTable(),
			'model_data' => $model->toJson(),
	    ];
	}
}
