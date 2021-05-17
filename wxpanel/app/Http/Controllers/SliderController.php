<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SliderController extends NewsController
{
	
    public function __construct(Request $request)
    {
		
		Controller::__construct($request);
		
        $this->resource = 'slider';
		$this->resourceLabel = 'Slider';
		$this->filterNote = \config('appCustom.MOD_NEWSSLIDER_FILTER');
		$this->viewPrefix = 'news.';
		
		$this->relationsCnt = true;
		
		
    }
    
}
