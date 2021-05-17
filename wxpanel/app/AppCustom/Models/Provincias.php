<?php
namespace App\AppCustom\Models;


class Provincias extends ModelCustomBase
{
	
	
    protected $table = 'provincias';
	protected $primaryKey = 'id';
    
    
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