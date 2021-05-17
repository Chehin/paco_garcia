<?php
namespace App\AppCustom\Models;

class TipoComprobanteLetra extends ModelCustomBase
{
	
    protected $table = 'tipo_comprobante_letras';
    
    
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
        'deleted_at',
    ];
	
}