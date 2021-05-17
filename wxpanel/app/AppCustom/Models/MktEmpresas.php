<?php
namespace App\AppCustom\Models;

class MktEmpresas extends ModelCustomBase
{
	
    protected $table = 'mkt_empresas';
    
    protected static $mkt_personasModel = 'App\AppCustom\Models\MktPersonas';
    
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
     * Returns the personas relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function personas()
    {
        return $this->belongsToMany(static::$mkt_personasModel, 'mkt_personas_empresas', 'id_empresa', 'id_persona')->withTimestamps()->select(array('id', \DB::raw('CONCAT(nombre, " ", apellido) as text')));
    }
}