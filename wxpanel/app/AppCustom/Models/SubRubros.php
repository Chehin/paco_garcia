<?php
namespace App\AppCustom\Models;

class SubRubros extends ModelCustomBase
{
	
    protected $table = 'inv_subrubros';
    
    
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
    
    

    public function equivalencias()
    {
        return $this->belongsToMany('App\AppCustom\Models\SubRubros', 'inv_subrubros_genero_marca','inv_subsubrubros_id', 'conf_marcas_id','conf_generos_id','imagen');
    }
	
}