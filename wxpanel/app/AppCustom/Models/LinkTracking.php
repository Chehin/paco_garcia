<?php
namespace App\AppCustom\Models;

class LinkTracking extends ModelCustomBase
{
	
    protected $table = 'link_tracking';
    
    
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