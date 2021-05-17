<?php
namespace App\AppCustom\Models;

class Genero extends ModelCustomBase
{
	
    protected $table = 'conf_generos';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $guarded = [];
	
	
	public $timestamps = false;
	
}