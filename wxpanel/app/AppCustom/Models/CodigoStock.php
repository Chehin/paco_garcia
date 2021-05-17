<?php

namespace App\AppCustom\Models;

class CodigoStock extends ModelCustomBase
{
    protected $table = 'inv_producto_codigo_stock';
    
    
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
    ];
	
   
}
