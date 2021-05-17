<?php
namespace App\Http\Controllers;
use App\AppCustom\Util;
use App\AppCustom\Models\Colores;

class ColoresUtilController extends GenericUtilController
{
    public function __construct(ColoresController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		//$this->itemNameField = 'titulo';
		
		
		$this->aExtraParams['imageCropW'] = 74;
		$this->aExtraParams['imageCropH'] = 74;
	}
		public function showMainViewImage($id) {
	
		$aResult = Util::getDefaultArrayResult();
        
        if ($item = Colores::find($id)) {
            if ($this->user->hasAccess($this->resource . '.view')) {
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
	
}
