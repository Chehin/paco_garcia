<?php

namespace App\AppCustom\Models;

class ConfTallesEquivalencias extends ModelCustomBase
{
    protected $table = 'conf_talles_equivalencias';
    
    
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    public $timestamps = false;
}
