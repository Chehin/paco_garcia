<?php
namespace App\AppCustom\Models;

class Colores extends ModelCustomBase
{
	
    protected $table = 'conf_colores';
	
    protected static $productosModel = 'App\AppCustom\Models\Productos';
    
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
	/**
     * Returns the colores relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productos()
    {
        return $this->belongsToMany(static::$productosModel, 'inv_producto_codigo_stock', 'id_color', 'id_producto', 'id_talle','stock','codigo')
		->withTimestamps()
		->select(array('id', 'nombre as text'));
    }
}