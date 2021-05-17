<?php

namespace App\AppCustom\Models;

class Listas extends ModelCustomBase
{
    protected $table = 'listas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['id_edicion', 'id_seccion', 'titulo', 'sumario','texto','categoria','antetitulo','ciudad','pais','keyword','_url','orden'];
    
    protected $guarded = [];

    protected $dates = [
        'updated_at',
        'fecha',
    ];
    
    public $timestamps = false;
}
