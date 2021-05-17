<?php
namespace App\AppCustom\Models;

class EmailTracking extends ModelCustomBase
{
	
    protected $table = 'email_tracking';
    
    
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