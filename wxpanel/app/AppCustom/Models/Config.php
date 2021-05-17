<?php

namespace App\AppCustom\Models;

class Config extends ModelCustomBase
{
    protected $table = 'config';
	//for id not numeric !important
	public $incrementing = false;
    
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
