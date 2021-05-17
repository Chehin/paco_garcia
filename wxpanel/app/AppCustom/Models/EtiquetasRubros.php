<?php
namespace App\AppCustom\Models;

class EtiquetasRubros extends ModelCustomBase
{
	
    protected $table = 'inv_etiquetas_rubros';
    
    
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