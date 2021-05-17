<?php

namespace App\AppCustom\Models;

class TipoComprobante extends ModelCustomBase
{
    protected $table = 'tipo_comprobante';
    
    
    protected $primaryKey = 'id_tipo_comprobante';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
    //$guarded property should contain an array of attributes that you do not want to be mass assignable
    protected $guarded = [];
    
    public $timestamps = false;
}
