<?php

namespace App\AppCustom\Models;

class NoteRelated extends ModelCustomBase
{
    protected $table = 'editorial_relacion_notas';
    
    
    protected $primaryKey = 'id_relacion_notas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
        
    protected $guarded = [];
    
    public $timestamps = false;
}
