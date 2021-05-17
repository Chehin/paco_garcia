<?php
namespace App\AppCustom\Models;

class TemplatesEditables extends ModelCustomBase
{
	
    protected $table = 'templates_editables';
    
    
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
    public $timestamps = false;
	
}