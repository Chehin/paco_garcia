<?php
namespace App\AppCustom\Models;

class MisEnvios extends ModelCustomBase
{
	
    protected $table = 'mis_envios';
    
    
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
}