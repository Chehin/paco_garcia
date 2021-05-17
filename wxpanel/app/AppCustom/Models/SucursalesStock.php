<?php
namespace App\AppCustom\Models;

class SucursalesStock extends ModelCustomBase
{
	
    protected $table = 'inv_producto_stock_sucursal';
    
    
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