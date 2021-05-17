<?php
namespace App\AppCustom\Models;

class Deportes extends ModelCustomBase
{
	
    protected $table = 'inv_deportes';
    
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
     * Returns the deportes relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productos()
    {
        return $this->belongsToMany(static::$productosModel, 'inv_productos_deportes', 'id_deporte', 'id_producto')->withTimestamps()->select(array('id', 'nombre as text'));
    }
}