<?php
namespace App\AppCustom\Models;

class Etiquetas extends ModelCustomBase
{
	
    protected $table = 'inv_etiquetas';
    
    protected static $rubrosModel = 'App\AppCustom\Models\Rubros';

    protected static $productosModel = 'App\AppCustom\Models\Productos';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $guarded = [];
	
	
	/**
     * The attributes thatAC should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Returns the etiquetas relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rubros()
    {
        return $this->belongsToMany(static::$rubrosModel, 'inv_etiquetas_rubros', 'id_etiqueta', 'id_rubro')->withTimestamps()->select(array('id', 'nombre as text'));
    }
	
    /**
     * Returns the etiquetas relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productos()
    {
        return $this->belongsToMany(static::$productosModel, 'inv_productos_etiquetas', 'id_etiqueta', 'id_producto')->withTimestamps()->select(array('id', 'nombre as text'));
    }
}