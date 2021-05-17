<?php
namespace App\AppCustom\Models;

class SubSubRubrosGeneroMarca extends ModelCustomBase
{
	
    protected $table = 'inv_subrubros_genero_marca';
    protected $primaryKey = 'id';
    
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
    // protected $dates = [
    //     'created_at',
    //     'updated_at',
    // ];
    
    public $timestamps = false;


    
}