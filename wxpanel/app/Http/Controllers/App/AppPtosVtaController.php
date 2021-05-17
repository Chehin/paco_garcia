<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AppPtosVtaController extends Controller
{
	public function __construct(Request $request)
    {
		$this->modelName = 'App\AppCustom\Models\PtosVta';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$aResult = Util::getDefaultArrayResult();		
		$modelName = $this->modelName;

		$ptosVta = $modelName::
		select('id','nombre','domicilio','ciudad','email','telefono','horarios','geo_latlong')
		->where('habilitado',1)
		->get();
		foreach($ptosVta as $local){
			$foto = '';
			$foto = 
			\App\AppCustom\Models\Image::
			select('imagen_file')
			->where('resource', 'ptosVta')
			->where('resource_id', $local->id)
			->where('habilitado', 1)
			->orderBy('destacada','desc')
			->orderBy('orden','asc')
			->first();
			$item = array(
				'id' => $local->id,
				'nombre' => $local->nombre,
				'domicilio' => $local->domicilio,
				'ciudad' => $local->ciudad,
				'email' => $local->email,
				'telefono' => $local->telefono,
				'horarios' => $local->horarios,
				'geo_latlong' => $local->geo_latlong,
				'foto' => ($foto?$foto->imagen_file:'')
			);
			array_push($aResult['data'],$item);
		}
		return $aResult;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
