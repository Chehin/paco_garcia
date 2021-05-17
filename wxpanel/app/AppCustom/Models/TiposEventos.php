<?php
namespace App\AppCustom\Models;

class TiposEventos extends ModelCustomBase
{
	
    protected $table = 'listas_tipos_eventos';
    
    
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