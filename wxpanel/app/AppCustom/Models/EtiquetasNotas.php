<?php
namespace App\AppCustom\Models;

class EtiquetasNotas extends ModelCustomBase
{
	
    protected $table = 'inv_etiquetas_blog';

    protected static $notesModel = 'App\AppCustom\Models\Note';
    
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
    /**
     * Returns the etiquetas relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function blog()
    {
        return $this->belongsToMany(static::$notesModel, 'editorial_notas_etiquetas', 'id_etiqueta', 'id_nota')->withTimestamps()->select(array('id', 'nombre as text'));
    }
}