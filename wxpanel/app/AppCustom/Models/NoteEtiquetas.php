<?php
namespace App\AppCustom\Models;

class NoteEtiquetas extends ModelCustomBase
{
    protected $table = 'editorial_notas_etiquetas';
    
    
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