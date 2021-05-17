<?php

namespace App\Http\Controllers;

use App\AppCustom\Util;

use App\AppCustom\Models\CodigoStock;
use Illuminate\Http\Request;

class GenericUtilController extends Controller
{
	public $itemNameField;

	public $aExtraParams = [
		'imageCropW' => null, 
		'imageCropH' => null, 
		'imageThumbProportion' => null,
	] 
	;
	
	public function showMainView(Request $request) { 

		if ($this->user->hasAccess($this->resource . '.view')) {
			$aViewData = array();
			$aViewData['prefix'] = isset($this->viewPrefix) ? $this->viewPrefix : '';
			$aViewData['resource'] = $this->resource;
			$aViewData['resourceLabel'] = $this->resourceLabel;			
			$aViewData['aCustomViewData'] = isset($this->aCustomViewData) ? $this->aCustomViewData : null;
			$aViewData['rubros'] = Util::getFiltroRubros();	
			$aViewData['aMarcas'] = Util::filtroMarcas();	
			$aViewData['listas'] = Util::getFiltroListas();	
			$aViewData['report'] = Util::getReporte();
			$aViewData['reportAB'] = Util::getReporteAB();
			$aViewData['aEtiquetas'] = Util::filtroEtiquetas();
		
			return 
				\View::make($this->viewPrefix . $this->resource . '.' . $this->resource)
					->with('aViewData', $aViewData)
				;
		} else {
				return \View::make('errors.unauthorized');
		}
	}

	public function showMainViewReport(Request $request,$id) { 

		if ($this->user->hasAccess($this->resource . '.view')) {
			$aViewData = array();
			$aViewData['resource'] = $this->resource;
			$aViewData['resourceLabel'] = $this->resourceLabel;
			
			$aViewData['aCustomViewData'] = isset($this->aCustomViewData) ? $this->aCustomViewData : null;	
			$aViewData['ratiosA'] = Util::getRatiosA($id);	
			$aViewData['ratiosB'] = Util::getRatiosB($id);	
			$aViewData['ratiosG'] = Util::getRatiosG($id);		
			
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
		
		$modelName = $this->modelName;
        
        if ($item = $modelName::find($id)) {
            if ($this->user->hasAccess($this->resource . '.view')) {
				
				
				$aViewData['item'] = $item;
				$aViewData['itemNameField'] = $this->itemNameField;
				$aViewData['resource'] = $this->resource;
				$aViewData['resourceLabel'] = $this->resourceLabel;
				$aViewData['aColores'] = CodigoStock::select('conf_colores.nombre', 'conf_colores.id')
				->leftJoin('conf_colores','conf_colores.id','=','inv_producto_codigo_stock.id_color')
				->where('inv_producto_codigo_stock.id_producto',$id)
				->where('conf_colores.habilitado',1)->lists('nombre','id');
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
