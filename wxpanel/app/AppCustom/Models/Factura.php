<?php

namespace App\AppCustom\Models;

class Factura extends ModelCustomBase
{
    protected $table = 'fe_facturas';
    
    
    protected $primaryKey = 'id_factura';
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
