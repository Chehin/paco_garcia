<?php
namespace App\AppCustom\Models;

class VistaFactura extends ModelCustomBase
{
	
    protected $table = 'vistafacturasdetalle';
    protected $primaryKey = 'id_cobranza_admin';
    
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
    public $timestamps = false;
	
}