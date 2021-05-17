<?php

namespace App\AppCustom\Models;

class Note extends ModelCustomBase
{
    protected $table = 'editorial_notas';

    protected static $etiquetasModel = 'App\AppCustom\Models\Etiquetas';
    protected static $etiquetasBlogModel = 'App\AppCustom\Models\EtiquetasNotas';
    
    protected $primaryKey = 'id_nota';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['id_edicion', 'id_seccion', 'titulo', 'sumario','texto','categoria','antetitulo','ciudad','pais','keyword','_url','orden'];
    
    protected $guarded = [];
    
    public $timestamps = false;

    /**
     * Returns the etiquetas relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function etiquetas()
    {
        return $this->belongsToMany(static::$etiquetasModel, 'editorial_notas_etiquetas', 'id_nota', 'id_etiqueta')->withTimestamps()->select(array('id_etiqueta as id', 'nombre as text'));
    }

    public function etiquetasBlog()
    {
        return $this->belongsToMany(static::$etiquetasBlogModel, 'editorial_notas_etiquetas', 'id_nota', 'id_etiqueta')->withTimestamps()->select(array('id_etiqueta as id', 'nombre as text'));
    }
}
