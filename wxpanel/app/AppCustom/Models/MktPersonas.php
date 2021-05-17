<?php
namespace App\AppCustom\Models;

class MktPersonas extends ModelCustomBase
{
	
    protected $table = 'mkt_personas';
    
    protected static $mkt_empresasModel = 'App\AppCustom\Models\MktEmpresas';
    protected static $mkt_oportunidadesModel = 'App\AppCustom\Models\MktOportunidades';
    protected static $mkt_listasModel = 'App\AppCustom\Models\MktListas';
    
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
     * Returns the empresas relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function empresas()
    {
        return $this->belongsToMany(static::$mkt_empresasModel, 'mkt_personas_empresas', 'id_persona', 'id_empresa')->withTimestamps()->select(array('id', 'razon_social as text'));
    }
    
    /**
     * Returns the empresas relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function oportunidades()
    {
        return $this->belongsToMany(static::$mkt_oportunidadesModel, 'mkt_personas_oportunidades', 'id_persona', 'id_oportunidad')->withTimestamps()->select(array('id', 'nombre as text'));
    }
    
    /**
     * Returns the empresas relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function listas()
    {
        return $this->belongsToMany(static::$mkt_listasModel, 'mkt_personas_listas', 'id_persona', 'id_lista')->withTimestamps()->select(array('id', 'nombre as text'));
    }
}