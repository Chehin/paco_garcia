<?php
namespace App\AppCustom\Models;


class Localidades extends ModelCustomBase
{
	
    protected $table = 'provincias_localidades';
    
    public $timestamps = false;
    
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
	   
}