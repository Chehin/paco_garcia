<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\AppCustom\Models\Image;

use Illuminate\Http\Request;
use Sentinel;
use Authorizer;
use App\AppCustom\Models\Sentinel\User;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
	
	protected $user;
	protected $id_company;
	
	public $resource;
    public $resourceLabel;
	public $filterNote;
	public $viewPrefix = '';
	public $modelName = '';
	
	protected $aCustomViewData = [];
	
	/**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request = null)
    {
		set_time_limit(120);

		if ($request) {
			if ($request->is(config('appCustom.clientRestPrefix') . '*')) {
				$this->user = Sentinel::findById(Authorizer::getResourceOwnerId());
				// $this->user = \App\AppCustom\Models\Sentinel\User::find(\config('appCustom.idInternalFeUser'));
			} elseif ($request->is(config('appCustom.frontClientRestPrefix') . '*')) {
				$this->user = User::where('id',config('appCustom.frontClientRestID'))->where('enabled',1)->first();
			} else {
				$this->user = Sentinel::getUser();
			}
			
			$this->id_company = 1;
			
		}
        
    }
	/**
     * Company info from this resource controller
     *
     */
	protected function getCompanyInfo() {
		$aReturn = [];
		
		$aReturn['company'] = \App\AppCustom\Models\Company::find($this->id_company);
		$aReturn['logos'] = \App\AppCustom\Util::getLogosByCompanyId($this->id_company);
		
		return $aReturn;
	}
	/**
     * Images count from this resource controller
     *
     */
	protected function putImgCnt(&$aItems) {
		
		array_walk($aItems, function(&$val){
				$val['imgCnt'] = 
					\App\AppCustom\Models\Image::ByCompany($this->id_company)
						->where('resource', $this->resource)
						->where('resource_id', $val['id'])
						->count()
				;
			});
		
	}
	
	protected function putImgInfo(&$aItems) {
		
		array_walk($aItems, function(&$val){
				$val['imgCnt'] = 
					\App\AppCustom\Models\Image::ByCompany($this->id_company)
						->where('resource', $this->resource)
						->where('resource_id', $val['id'])
						->count()
				;
				
				$img = $this->imgs($val['id'], 1);
				
				$val['imgFirst'] = isset($img[0]) ? $img[0]->imagen_file : null;

			});
		
	}
	
	protected function imgs($resourceId, $limit=null) {
		
		$items =
		Image::ByCompany($this->id_company)
			->where('resource', $this->resource)
			->where('resource_id', $resourceId)
			->where('habilitado', 1)
			->orderBy('destacada', 'desc')
			->orderBy('orden', 'asc')
		;
		
		if ($limit) {
			$items->limit($limit);
		}
		
		return $items->get();
		
	}
	/**
     * Relations count from this resource controller
     *
     */
	protected function putRelationCnt(&$aItems) {
		
		array_walk($aItems, function(&$val){
				$val['relationCnt'] = 
					\App\AppCustom\Models\ItemRelated::ByCompany($this->id_company)
						->where('parent_resource', $this->resource)
						->where('parent_id', $val['id'])
						->count()
				;
			});
		
	}
	/**
     * Delete images from this resource controller
     *
     */
	protected function deleteImgs($resourceId) {
		
		$imgs = \App\AppCustom\Models\Image::ByCompany($this->id_company)
			->where('resource_id', $resourceId)
			->where('resource', $this->resource)
			->select('id', 'imagen_file')
			->get()
		;
				
		if (!$imgs->isEmpty()) {
			foreach ($imgs as $img) {
				@unlink(\config('appCustom.UPLOADS_BE') . $img->imagen_file);
				@unlink(\config('appCustom.UPLOADS_BE') . 'app_' . $img->imagen_file);
				@unlink(\config('appCustom.UPLOADS_BE') . 'th_' .  $img->imagen_file);
				
				\App\AppCustom\Models\Image::destroy($img->id);
				
			}


		}
	}
}
