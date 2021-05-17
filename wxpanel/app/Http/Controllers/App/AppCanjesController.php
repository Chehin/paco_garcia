<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AppCanjesController extends Controller
{
	public function __construct(Request $request)
    {
		$this->modelName = 'App\AppCustom\Models\Premio';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //traigo premios, categorias, puntos disponibles, historial de canjes
		$aResult = Util::getDefaultArrayResult();		
		$modelName = $this->modelName;
		///////////
		$items = $modelName::
		leftJoin('categorias_premios as a','a.id','=','premios.id_categoria')
		->where('premios.habilitado',1)
		->where('premios.stock','>',0);
		
		///////////
		$categorias = $items->select('a.nombre as categoria','a.id')->orderBy('a.nombre')->get()->toArray();
		$aResult['data']['categorias'] = $categorias;
		
		///////////
		$premios = $items->select('premios.id','premios.titulo','premios.descripcion','a.nombre as categoria','premios.puntos')->get();
		$aResult['data']['premios'] = array();
		foreach($premios as $premio){
			$fotos = '';
			$fotos = 
			\App\AppCustom\Models\Image::
			select('imagen_file','epigrafe')
			->where('resource', 'premios')
			->where('resource_id', $premio->id)
			->where('habilitado', 1)
			->orderBy('destacada','desc')
			->orderBy('orden','asc')
			->get()->toArray();
			$item = array(
				'id' => $premio->id,
				'titulo' => $premio->titulo,
				'descripcion' => $premio->descripcion,
				'categoria' => $premio->categoria,
				'puntos' => $premio->puntos,
				'fotos' => $fotos
			);
			array_push($aResult['data']['premios'],$item);
		}
		
		///////////
		$pesos_punto = \App\AppCustom\Models\Config::find('PESO_PUNTOS')->value;
		$puntosCliente = (\App\AppCustom\Models\Cliente::find($request->input('id_cliente'))->compras) * $pesos_punto;
		$aResult['data']['puntos'] = $puntosCliente;
		
		///////////
		$canjes = \App\AppCustom\Models\Canje::
		select('canjes.id','b.titulo','canjes.created_at as fecha', 'a.nombre as categoria','b.puntos')
		->leftJoin('premios as b','b.id','=','canjes.id_premio')
		->leftJoin('categorias_premios as a','a.id','=','b.id_categoria')
		->where('canjes.id_cliente','=',$request->input('id_cliente'))
		->orderBy('canjes.created_at', 'desc')
		->get()->toArray();
		$aResult['data']['historial'] = $canjes;
		
		return $aResult;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$modelName = $this->modelName;
		
        //nuevo canje
		$aResult = Util::getDefaultArrayResult();
		//reviso si tiene puntos suficientes
		$pesos_punto = \App\AppCustom\Models\Config::find('PESO_PUNTOS')->value;
		$puntosCliente = (\App\AppCustom\Models\Cliente::find($request->input('id_cliente'))->compras) * $pesos_punto;
		$puntosPremio = $modelName::find($request->input('id_premio'))->puntos;
		
		if($puntosCliente>=$puntosPremio){
			$resource = new \App\AppCustom\Models\Canje_Solicitud(['id_cliente' => $request->input('id_cliente'),'id_premio' => $request->input('id_premio')]);
			
			if (!$resource->save()) {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.dbError');
			} else {
				$aResult['msg'] = 'Su solicitud de canje se ha enviado con éxito, será notificado cuando se valide la misma';
			}
		}else{
			$aResult['status'] = 1;
				$aResult['msg'] = 'Sus puntos no son suficientes para el canje de este premio';
		}
		return $aResult;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
