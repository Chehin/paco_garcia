<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Image;
use Sentinel;
use App\AppCustom\Models\Colores;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
		$aParams = ImageUtilController::getParameters($request);
		
		if (empty($aParams)) {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.wrongRequest');
			
			return response()->json($aResult);
		}
        
        if ($this->user->hasAccess($aParams['resource'] . '.view')) {
            
            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            $currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 1;
                $sortDir = 'asc';
            } else {
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $aOItems = 
                Image::ByCompany($this->id_company)
                    ->select(
                        'id',
                        'imagen',
						'imagen_file',
                        'epigrafe',
						'orden',
                        'destacada',
						'id_color',
                        'habilitado'
                    )
					->where('resource', $aParams['resource'])
					->where('resource_id', $request->input('resource_id'))
                    ->orderBy($sortCol, $sortDir)
            ;

            if ($search) {
                $aOItems
                    ->where('imagen','like',"%{$search}%")
                    ->orWhere('epigrafe','like',"%{$search}%")
                ;
            }

            $aOItems = $aOItems
                ->paginate($pageSize)
            ;

            $aItems = $aOItems->toArray();
			
			array_walk($aItems['data'], function(&$val,$key){
                $val['imagen_file']	= \config('appCustom.PATH_UPLOADS') . $val['imagen_file'];
                if(isset(Colores::find($val['id_color'])->nombre)){
                    $val['color'] = ($val['id_color']?Colores::find($val['id_color'])->nombre:'');
                }
				
			});
			
            $total = $aItems['total'];
            $aItems = $aItems['data'];

            $aResult['data'] = $aItems;
            $aResult['recordsTotal'] = $total;
            $aResult['recordsFiltered'] = $total;
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$aResult = Util::getDefaultArrayResult();
		
		$aParams = ImageUtilController::getParameters($request);
		
		if (empty($aParams)) {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.wrongRequest');
			
			return response()->json($aResult);
		}
        
        if (Sentinel::hasAccess($aParams['resource'] . '.update')) {
        
            //Validation
            $validator = \Validator::make(
                $request->all(), 
                array(
                    'image-data' => 'required',
					'name' => 'required',
                ), 
                array(
                    'image-data.required' => 'La imagen es requerida',
					'name.required' => 'El nombre de la imagen es requerido',
                )
            );
			
            if (!$validator->fails()) {
				$fileName = \time();
                $fileName .= '_' . \base64_encode($request->input('name'));
				$fileName .= '.jpg';
				
				Util::uploadBase64File(
					\config('appCustom.UPLOADS_BE'),
					$fileName, 
					$request->input('image-data'),
					$request->input('imageThumbProportion')	
				)
				;
				
                $resource = new Image(
					[
					'resource' => $aParams['resource'],
					'resource_id' => $request->input('resource_id'),
					'id_company' => $this->id_company,
					'imagen' => $request->input('name'),
					'imagen_file' => $fileName,
					'orden' => $request->input('order'),
					'epigrafe' => $request->input('epigraph'),
					'id_color' => $request->input('id_color'),
					'habilitado' => 1,
					]
                )
                ;
				
                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = $validator->errors()->all();
            }
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }  
        
        return response()->json($aResult);
    }
	

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        
        $item = Image::findByCompany($id, $this->id_company);
                
        if ($item) {
            $aResult['data'] = $item->toArray();
            $aResult['data']['imagen_file'] = \config('appCustom.PATH_UPLOADS') . $aResult['data']['imagen_file'];        
			$aResult['data']['id'] = $aResult['data']['id'];
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }
        
        
        return response()->json($aResult);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$aResult = Util::getDefaultArrayResult();
		
		$aParams = ImageUtilController::getParameters($request);
		
		if (empty($aParams)) {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.wrongRequest');
			
			return response()->json($aResult);
		}
        
        if (Sentinel::hasAccess($aParams['resource'] . '.update')) {
        
            $item = Image::findByCompany($id, $this->id_company);

            if ($item) {
                //Just enable/disable resource?
                if ('yes' === $request->input('justEnable')) {
                    $item->habilitado = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    return response()->json($aResult);
                }
				//Just enable/disable1 resource?
                if ('yes' === $request->input('justEnable1')) {
					Image::where('resource_id',$request->input('resource_id'))
						->where('resource',$aParams['resource'])
						->update(['destacada' => 0])
						;
					
                    $item->destacada = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    return response()->json($aResult);
                }

                //Validation
				$validator = \Validator::make(
					$request->all(), 
					array(
						'image-data' => 'required',
						'name' => 'required',
					), 
					array(
						'image-data.required' => 'La imagen es requerida',
						'name.required' => 'El nombre de la imagen es requerido',
					)
				);

                if (!$validator->fails()) {
					
					$fileName = \time();
					$fileName .= '_' . \base64_encode($request->input('name'));
					$fileName .= '.jpg';
					
					@unlink(\config('appCustom.UPLOADS_BE') . $item->imagen_file);

					Util::uploadBase64File(
						\config('appCustom.UPLOADS_BE'), 
						$fileName, 
						$request->input('image-data'),
						$request->input('imageThumbProportion')
					)
					;

					$item->fill(
						[
							'imagen' => $request->input('name'),
							'imagen_file' => $fileName,
							'orden' => $request->input('order'),
							'epigrafe' => $request->input('epigraph'),
							'id_color' => $request->input('id_color'),
						]
					)
					;

					if (!$item->save()) {
						$aResult['status'] = 1;
						$aResult['msg'] = \config('appCustom.messages.dbError');
					}

                } else {
                    $aResult['status'] = 1;
                    $aResult['msg'] = $validator->errors()->all();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
		$aResult = Util::getDefaultArrayResult();
		
		$aParams = ImageUtilController::getParameters($request);
		
		if (empty($aParams)) {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.wrongRequest');
			
			return response()->json($aResult);
		}
        
        if (Sentinel::hasAccess($aParams['resource'] . '.update')) {
        
            $item = Image::findByCompany($id, $this->id_company);

            if ($item) {
				@unlink(\config('appCustom.UPLOADS_BE') . $item->imagen_file);
				@unlink(\config('appCustom.UPLOADS_BE') . 'app_' . $item->imagen_file);
				@unlink(\config('appCustom.UPLOADS_BE') . 'th_' .  $item->imagen_file);
				$item->delete();
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
