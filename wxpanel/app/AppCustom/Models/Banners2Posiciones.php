<?php
namespace App\AppCustom\Models;

class Banners2Posiciones extends ModelCustomBase
{
	
    protected $table = 'banners2_posiciones';
    
    
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