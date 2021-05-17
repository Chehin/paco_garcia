<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Productos;
use App\Http\Controllers\Fe\FeUtilController;
use App\AppCustom\Models\ProductosColorRelated;
use Sentinel;


class ProductosRelatedColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //Related notes
    public function index(Request $request)
    {
        //no tiene rubros lo de paco garcia por eso comento en la consulta.
        $aResult = Util::getDefaultArrayResult();
        
        $aParams = ProductosRelatedColorUtilController::getParameters($request);
                
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
                $sortCol = 'id';
                $sortDir = 'asc';
            } else {
                $sortCol = 'inv_productos.' . $sortCol;
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));

            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });
            
            $aOItems = 
                Productos::
                    select(
                        'inv_productos.id',
                        'inv_producto_codigo_stock.codigo',
                        'inv_productos.nombre',
                        'inv_productos.nombremeli',
                        'inv_rubros.nombre as rubro',
                        'inv_productos.id_subrubro',
                        'inv_productos.id_subsubrubro',
                        'inv_productos.orden',
                        'inv_productos.habilitado',
                        'inv_productos.destacado',
                        'inv_productos.oferta',
                        'inv_productos.comentarios',
                        'inv_productos.id_meli',
                        'inv_productos.estado_meli',
                        'a.id_principal',
                        'a.id_secundaria'
                    )
                    ->join("inv_productos_color_relacion as a","a.id_secundaria","=","inv_productos.id")
                    ->leftJoin("inv_rubros","inv_rubros.id","=","inv_productos.id_rubro")
                    ->join('inv_producto_codigo_stock','inv_producto_codigo_stock.id_producto','=','inv_productos.id')
                    /* ->leftJoin("conf_marcas","conf_marcas.id","=","inv_productos.id_marca") */
                    ->where('a.id_principal', $request->input('id'))
                    ->orderBy($sortCol, $sortDir)->groupBy('inv_productos.id')
            ;

            if ($search) {
                $aOItems
                    ->where(function($query) use ($search) {
                        $query
                            ->where('inv_productos.nombre','like',"%{$search}%")
                            ->orWhere('inv_productos.modelo','like',"%{$search}%")
                        ;
                    });
            }

            $aOItems = $aOItems
                ->paginate($pageSize)
            ;

            $aItems = $aOItems->toArray();          
            
            array_walk($aItems['data'], function(&$val,$key){
				                
                if(isset($val['id_subsubrubro'])){
                    $val['rubro'] = $val['rubro'].($val['id_subrubro']?' > '.SubRubros::find($val['id_subrubro'])->nombre:'').($val['id_subsubrubro']?' > '.SubSubRubros::find($val['id_subsubrubro'])->nombre:'');
                }
                $aOItems = FeUtilController::getImages($val['id'],1, 'productos');
                if($aOItems){
                    $val['foto'] = \config('appCustom.PATH_UPLOADS').'th_'.$aOItems[0]['imagen_file'];
                }else{
                    $val['foto'] = '';
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
        //
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
        //
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
        
        $aParams = ProductosRelatedColorUtilController::getParameters($request);
        
        if (empty($aParams)) {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.wrongRequest');
            
            return response()->json($aResult);
        }
        
        list($primaryId, $productoId) = explode('_', $id);
        
        
        if ($this->user->hasAccess($aParams['resource'] . '.update')) {
            
            $item = Productos::find($primaryId);
            
            if ($item) {
                
                $alredyRelated = 
                    ProductosColorRelated::where('id_principal', $primaryId)
                        ->where('id_secundaria', $productoId)
                ;
                
                if (0 == $alredyRelated->count()) {
                    
                    if ($primaryId != $productoId) {
                                        
                        $noteRelated = 
                            new ProductosColorRelated(
                                [
                                    'id_principal' => $primaryId,
                                    'id_secundaria' => $productoId,
                                ]
                            )
                            ;
                        if (!$noteRelated->save()) {
                            $aResult['status'] = 1;
                            $aResult['msg'] = \config('appCustom.messages.dbError');
                        }

                        $noteRelated1 = 
                            new ProductosColorRelated(
                                [
                                    'id_principal' => $productoId,
                                    'id_secundaria' => $primaryId,
                                ]
                            )
                            ;
                        if (!$noteRelated1->save()) {
                            $aResult['status'] = 1;
                            $aResult['msg'] = \config('appCustom.messages.dbError');
                        }
                    } else {
                        $aResult['status'] = 1;
                        $aResult['msg'] = 'No se puede autorelacionar el producto';
                    }
                } else {
                    $aResult['status'] = 1;
                    $aResult['msg'] = 'Ya se ha relacionado este producto';
                }
                
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
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
        
        $aParams = ProductosRelatedColorUtilController::getParameters($request);
        
        if (empty($aParams)) {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.wrongRequest');
            
            return response()->json($aResult);
        }
        
        list($primaryId, $productoId) = explode('_', $id);
        
        
        if ($this->user->hasAccess($aParams['resource'] . '.update')) {
            
            $item = Productos::find($primaryId);
            
            if ($item) {
                
                $alredyRelated = 
                    ProductosColorRelated::where('id_principal', $primaryId)
                        ->where('id_secundaria', $productoId)
                        
                ;

                $alredyRelated1 = 
                    ProductosColorRelated::where('id_principal', $productoId)
                        ->where('id_secundaria', $primaryId)
                        
                ;                    
                
                if ($alredyRelated->count() > 0 && $alredyRelated1->count() > 0) {
                    
                    if (!$alredyRelated->delete()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }

                    if (!$alredyRelated1->delete()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');
                    }
                } else {
                    $aResult['status'] = 1;
                    $aResult['msg'] = 'El producto no está relacionado';
                }
                
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        }
        
        return response()->json($aResult);
    }
}
