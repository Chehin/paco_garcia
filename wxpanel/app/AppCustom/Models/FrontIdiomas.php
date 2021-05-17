<?php

namespace App\AppCustom\Models;

class FrontIdiomas extends ModelCustomBase
{
    protected $table = 'front_idiomas';
    
    
    protected $primaryKey = 'id_idioma';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    public $timestamps = false;
}
