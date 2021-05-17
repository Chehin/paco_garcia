<?php

namespace App\AppCustom\Models;

class Category extends ModelCustomBase
{
    protected $table = 'editorial_secciones';
    
    
    protected $primaryKey = 'id_seccion';
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
