<?php
namespace App\AppCustom\Models;

class CampaignListas extends ModelCustomBase
{
	
    protected $table = 'campaign_listas';
    
    
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