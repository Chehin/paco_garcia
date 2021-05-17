<?php
namespace App\AppCustom\Models;

class MktListasPersonas extends ModelCustomBase
{
	
    protected $table = 'mkt_personas_listas';
    
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