<?php
namespace App\Http\Controllers;
use App\AppCustom\Util;
use Illuminate\Http\Request;


class ProductosUtilController extends GenericUtilController
{
    public function __construct(ProductosController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		$this->aExtraParams['imageCropW'] = 800;
		$this->aExtraParams['imageCropH'] = 800;
		$this->aCustomViewData['rubros'] = Util::getFiltroRubros();
		//$this->itemNameField = 'titulo';
	}

	public function showMainViewPreciosRelated($id) {
	
		$aResult = Util::getDefaultArrayResult();
        
        if ($item = \App\AppCustom\Models\Productos::find($id)) {
            if ($this->user->hasAccess($this->resource . '.view')) {
				
				$aViewData['item'] = $item;
				$aViewData['aMonedas'] = \App\AppCustom\Models\Monedas::select('id','nombre')->lists('nombre','id');
				$aViewData['resource'] = $this->resource;
				$aViewData['resourceLabel'] = $this->resourceLabel;
				$aViewData['aCustomViewData'] = $this->aCustomViewData;
                
                $aResult['html'] = 
                    \View::make('productos.productos.productosPreciosRelatedMain')
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

	public function showMainViewProductosRelated($id) {
	
		$aResult = Util::getDefaultArrayResult();
        
        if ($item = \App\AppCustom\Models\Productos::find($id)) {
            if ($this->user->hasAccess($this->resource . '.view')) {
				
				$aViewData['item'] = $item;
				$aViewData['resource'] = $this->resource;
                $aViewData['resourceLabel'] = $this->resourceLabel;
                $aViewData['aMarcas'] = Util::filtroMarcas();	
                $aViewData['aCustomViewData'] = isset($this->aCustomViewData) ? $this->aCustomViewData : null;
                $aViewData['rubros'] = Util::getFiltroRubros();
            
                $aResult['html'] = 
                    \View::make('productos.productos.productosRelatedMain')
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
    
    public function showMainViewProductosRelatedColor($id) {
	
		$aResult = Util::getDefaultArrayResult();
        
        if ($item = \App\AppCustom\Models\Productos::find($id)) {
            if ($this->user->hasAccess($this->resource . '.view')) {
				
				$aViewData['item'] = $item;
				$aViewData['resource'] = $this->resource;
				$aViewData['resourceLabel'] = $this->resourceLabel;
                
                $aResult['html'] = 
                    \View::make('productos.productos.productosRelatedColorMain')
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

    public function showMainViewPreguntasRelated($id) {

        $aResult = Util::getDefaultArrayResult();

        if ($item = \App\AppCustom\Models\Productos::find($id)) {
            if ($this->user->hasAccess($this->resource . '.view')) {
                
                $aViewData['item'] = $item;
                $aViewData['preguntas'] = \App\AppCustom\Models\Preguntas::where('id_meli','=',$item->id_meli)->get();
                $aViewData['resource'] = $this->resource;
                $aViewData['resourceLabel'] = $this->resourceLabel;
                $aViewData['aCustomViewData'] = $this->aCustomViewData;
                
                $aResult['html'] = 
                    \View::make('productos.productos.productosPreguntasRelatedMain')
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
	
	public function filtroSubRubros(Request $request) { 

		$aViewData = Util::getFiltroSubRubros($request->id);                  

		return response()->json($aViewData);
              
    }
}
