<?php

namespace App\Http\Controllers;

use App\AppCustom\Models\Note;
use App\AppCustom\Util;
use App\AppCustom\Models\Category as Seccion;

class NewsUtilController extends Controller
{
    public $resource;
    public $resourceLabel;
	public $viewPrefix = '';
	
	public function __construct(NewsController $res) {
		
		//Controller::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		
		$this->aExtraParams['imageCropW'] = 500;
		$this->aExtraParams['imageCropH'] = 500;
	}
	
	
	public function showMainView() {

		if ($this->user->hasAccess($this->resource . '.view')) {
			$aViewData = array();
			$aViewData['resource'] = $this->resource;
			$aViewData['resourceLabel'] = $this->resourceLabel;
			
			$aViewData['secciones'] = Seccion::where('habilitado', 1)->get();

			return 
				\View::make($this->viewPrefix . $this->resource . '.' . $this->resource)
					->with('aViewData', $aViewData)
				;
		} else {
				return \View::make('errors.unauthorized');
		}
	}
	
	public function showMainViewImage($id) {
	
		$aResult = Util::getDefaultArrayResult();
        
        if ($item = Note::find($id)) {
            if ($this->user->hasAccess($this->resource . '.view')) {
				$item->id = $item->id_nota;
				
				$aViewData['item'] = $item;
				$aViewData['resource'] = $this->resource;
				$aViewData['resourceLabel'] = $this->resourceLabel;
				$aViewData['aColores'] = (isset($this->aColores)?$this->aColores:'');
				$aViewData['aImageCropSize'] =  
					[
					'w' => (isset($this->aExtraParams['imageCropW'])) ? $this->aExtraParams['imageCropW'] : \config('appCustom.image.cropSize.w'), 
					'h' => (isset($this->aExtraParams['imageCropH'])) ? $this->aExtraParams['imageCropH'] : \config('appCustom.image.cropSize.h'),
				];
                $aViewData['imageThumbProportion'] = 
					(isset($this->aExtraParams['imageThumbProportion'])) ? $this->aExtraParams['imageThumbProportion'] : \config('appCustom.image.thumbProportion');
                
                $aResult['html'] = 
                    \View::make('imageMain')
                        ->with('aViewData', $aViewData)
                        ->render()
                    ;
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.unauthorized');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }
        
        
        return response()->json($aResult);
	}
	
	public function showMainViewNoteRelated($id) {
	
		$aResult = Util::getDefaultArrayResult();
        
        if ($item = Note::find($id)) {
            if ($this->user->hasAccess($this->resource . '.view')) {
				
				$aViewData['item'] = $item;
				$aViewData['resource'] = $this->resource;
				$aViewData['resourceLabel'] = $this->resourceLabel;
                
                $aResult['html'] = 
                    \View::make('noteRelatedMain')
                        ->with('aViewData', $aViewData)
                        ->render()
                    ;
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.unauthorized');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }
        
        
        return response()->json($aResult);
	}
    
    
}
