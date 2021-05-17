<?php
namespace App\AppCustom\Models;

class ListasClientes extends ModelCustomBase
{
	
    protected $table = 'listas_usuarios';
    
    
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