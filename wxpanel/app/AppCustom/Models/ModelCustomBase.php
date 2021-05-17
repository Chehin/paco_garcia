<?php

namespace App\AppCustom\Models;

use Illuminate\Database\Eloquent\Model;
use App\AppCustom\Models\InternalLog;

class ModelCustomBase extends Model
{
	
	protected static function customMethodLog($modelEvent = null) {
	
		return function($model) use ($modelEvent) {
			if ((isset($model->customProperyNotLog) && (true === $model->customProperyNotLog)) ||
				! \config('appCustom.modelLogFeature')
			) {
				return true; //dont log
			}
			//Log...
			$aData = InternalLog::customMethodGetDefaultDataLog($model);
			$aData['model_event'] = $modelEvent;
			
			$log = new InternalLog($aData);
			
			$log->save();
		};
	}
	


	
    public static function boot()
    {
        parent::boot();
		
		static::updated(
			static::customMethodLog('updated')
		);
		
		static::created(
			static::customMethodLog('created')
		);
		
		static::deleted(
			static::customMethodLog('deleted')
		);
		
	}
	
	public function customMethodGetId() {
		return $this->{$this->primaryKey};
	}
	
	/**
     * Scope a query to only include current company.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCompany($query, $idCompany, $alias='')
    {
		
		if (!$alias) {
			$aliasSql = '';
		} else {
			$aliasSql = $alias . '.';
		}
		
        return $query->where($aliasSql . 'id_company', $idCompany);
    }
	
	/**
     * Find custom
     *
     * @return Model
     */
	static public function findByCompany($id, $idCompany) {
		
		$modelClass = \get_called_class();
		
		return
		$modelClass::where('id', $id)
			->where('id_company', $idCompany)
			->first()
		;
	}
}
