<?php
namespace App\AppCustom\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaSis extends ModelCustomBase
{
	
	use SoftDeletes;
	
    protected $table = 'companies';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $guarded = [];
	
	
	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
	
}