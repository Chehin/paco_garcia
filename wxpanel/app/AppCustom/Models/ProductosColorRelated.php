<?php

namespace App\AppCustom\Models;

class ProductosColorRelated extends ModelCustomBase
{
    protected $table = 'inv_productos_color_relacion';
    
    
    protected $primaryKey = 'id_relacion_productos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
        
    protected $guarded = [];
    
    public $timestamps = false;
}