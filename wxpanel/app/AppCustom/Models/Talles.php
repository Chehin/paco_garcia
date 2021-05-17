<?php
namespace App\AppCustom\Models;

class Talles extends ModelCustomBase
{
	
    protected $table = 'conf_talles';
    
    
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
    public $timestamps = false;
	
}