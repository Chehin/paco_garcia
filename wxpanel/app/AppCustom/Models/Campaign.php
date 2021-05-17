<?php
namespace App\AppCustom\Models;

class Campaign extends ModelCustomBase
{
	
    protected $table = 'campaign';
    
    
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
    protected $dates = [];
	
}