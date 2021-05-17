<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AppNotasController extends Controller
{
	public function __construct(Request $request)
    {
		$this->modelName = 'App\AppCustom\Models\Nota';
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

		$notas = $modelName::
		where('habilitado',1)
		->orderBy('destacado','desc')
		->orderBy('orden','asc')
		->get()->toArray();
		foreach($notas as $nota){
			$fotos = '';
			$fotos = 
			\App\AppCustom\Models\Image::
			select('imagen_file')
			->where('resource', 'notas')
			->where('resource_id', $nota['id'])
			->where('habilitado', 1)
			->orderBy('destacada','desc')
			->orderBy('orden','asc')
			->get()->toArray();
			$item = array(
				'fotos' => $fotos
			);
			$nota = $nota + $item;
			array_push($aResult['data'],$nota);
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
