<?php

namespace App\AppCustom\Models;

class ItemRelated extends ModelCustomBase
{
    protected $table = 'items_related';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $guarded = [];
   
    
    public $timestamps = false;
}
