<?php

namespace App\Http\Controllers;

use App\AppCustom\Util;
use App\AppCustom\Models\SubRubros;
use App\AppCustom\Models\SubSubRubros;
use App\AppCustom\Models\Generos;
use App\AppCustom\Models\Marcas;
use App\AppCustom\Models\SubSubRubrosGenerosMarca;

trait ResourceTraitController {
    
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aResult = Util::getDefaultArrayResult();
        $aViewData = [
            'mode' => 'add',
			'resource' => $this->resource,
            'resourceLabel' => $this->resourceLabel,
            'viewPrefix' => $this->viewPrefix,
			
			'aCustomViewData' => (isset($this->aCustomViewData) ? $this->aCustomViewData : null),
        ];
       
        $aViewData['aSubRubros'] = array('' => 'Seleccione una subrubro...');
        $aViewData['aSubSubRubros'] = array('' => 'Seleccione una subsubrubro...');   

        $aResult['html'] = \View::make($this->viewPrefix . $this->resource.".".$this->resource."Edit")
            ->with('aViewData', $aViewData)
            ->render()
        ;

        return response()->json($aResult);
    }
	
	
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $aResult = Util::getDefaultArrayResult();
		
		$modelName = $this->modelName;
        
        $item = $modelName::find($id);
        \Log::info(print_r($item->texto,true));
        
        if ($item) {

            $aViewData = [
                'mode'  => 'edit',
                'item' => $item,
				'resource' => $this->resource,
                'resourceLabel' => $this->resourceLabel,
				'viewPrefix' => $this->viewPrefix,
				
				'aCustomViewData' => (isset($this->aCustomViewData) ? $this->aCustomViewData : null),
				
				
            ];
            
            

    		if($item->id_rubro){
				$aViewData['aSubRubros'] = array('' => 'Seleccione una subrubro...') + SubRubros::where('id_rubro',$item->id_rubro)->where('habilitado',1)->lists('nombre', 'id')->toArray();
            }else{
                $aViewData['aSubRubros'] = array('' => 'Seleccione una subrubro...') + SubRubros::where('habilitado',1)->lists('nombre', 'id')->toArray();
            }
            
			if($item->id_subrubro){
				$aViewData['aSubSubRubros'] = array('' => 'Seleccione una subsubrubro...') + SubSubRubros::where('id_subrubro',$item->id_subrubro)->where('habilitado',1)->lists('nombre', 'id')->toArray();
            }
            
			if($item->id_genero){
                $aViewData['aGeneros'] = array('' => 'Seleccione un Género...') + Generos::select('id','genero')->orderBy('genero')->lists('genero','id')->toArray();
			}
            if($item->id){
				$aViewData['aMarcas'] = array('' => 'Seleccione una Marca...') + Marcas::select('id','nombre')->orderBy('nombre')->lists('nombre','id')->toArray();
			}

            $aResult['html'] = \View::make($this->viewPrefix . $this->resource . "." . $this->resource . "Edit")
                ->with('aViewData', $aViewData)
                ->render()
            ;
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
                
    }
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.delete')) {
			$modelName = $this->modelName;
        
            $item = $modelName::find($id);

            if ($item) {
                if (!$item->delete()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        
        return response()->json($aResult);
    }
	
	
}

