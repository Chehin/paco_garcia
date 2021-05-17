<?php
namespace App\AppCustom\Models;

class CampaignTestingLista extends ModelCustomBase
{
	
    protected $table = 'campaign_testing_lista';
    
    
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