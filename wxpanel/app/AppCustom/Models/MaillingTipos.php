<?php
namespace App\AppCustom\Models;

class MaillingTipos extends ModelCustomBase
{
	
    protected $table = 'tipos_mail';
    
    
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
    protected $dates = [];
	
}