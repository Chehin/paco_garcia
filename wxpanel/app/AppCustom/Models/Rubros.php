<?php
namespace App\AppCustom\Models;

class Rubros extends ModelCustomBase
{
	
    protected $table = 'inv_rubros';

    protected static $etiquetasModel = 'App\AppCustom\Models\Etiquetas';
    
    
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
        return $this->belongsToMany(static::$etiquetasModel, 'inv_etiquetas_rubros', 'id_rubro', 'id_etiqueta')->withTimestamps()->select(array('id', 'nombre as text'));
    }
	
}