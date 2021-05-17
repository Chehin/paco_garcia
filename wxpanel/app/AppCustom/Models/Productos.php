<?php
namespace App\AppCustom\Models;

class Productos extends ModelCustomBase
{
	
    protected $table = 'inv_productos';

    protected static $etiquetasModel = 'App\AppCustom\Models\Etiquetas';
    protected static $coloresModel = 'App\AppCustom\Models\Colores';
    protected static $tallesModel = 'App\AppCustom\Models\Talles';
    protected static $deportesModel = 'App\AppCustom\Models\Deportes';
    
    
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
     * Returns the etiquetas relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function etiquetas()
    {
        return $this->belongsToMany(static::$etiquetasModel, 'inv_productos_etiquetas', 'id_producto', 'id_etiqueta')->withTimestamps()->select(array('id', 'nombre as text'));
    }
    /**
     * Returns the deportes relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function deportes()
    {
        return $this->belongsToMany(static::$deportesModel, 'inv_productos_deportes', 'id_producto', 'id_deporte')->withTimestamps()->select(array('id', 'nombre as text'));
    }
    /**
     * Returns the colores relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function colores()
    {
        return $this->belongsToMany(static::$coloresModel, 'inv_producto_codigo_stock', 'id_producto', 'id_color','id_talle','stock','codigo')->withTimestamps()
		->select(array('inv_producto_codigo_stock.id', 'nombre as nombreColor','id_color','id_talle','stock','codigo','estado_meli'));
    }
    /**
     * Returns the colores relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function talles()
    {
        return $this->belongsToMany(static::$tallesModel, 'inv_producto_codigo_stock', 'id_producto', 'id_color','id_talle','stock','codigo')->withTimestamps()
		->select(array('nombre as nombreTalle','id_color','id_talle','stock','codigo','estado_meli'));
    }
	
}