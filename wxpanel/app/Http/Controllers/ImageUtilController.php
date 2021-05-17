<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Models\Image;

class ImageUtilController extends Controller
{
	
	static function getParameters(Request $request) {
		$routeName = $request->route()->getName();
	
		$aParams = [];

		if($request->is('*/empresaSisImage*')) {
			$res = new EmpresaSisController($request);
			$aParams['resource'] = $res->resource;
			$aParams['path'] =  config('appCustom.UPLOADS_EMPRESASIS_IMG');
		}
		
		if (strpos($routeName, 'rubrosImage') !== false) {
			$res = new RubrosController($request);
			$aParams['resource'] = $res->resource;
		}

		if (strpos($routeName, 'etiquetasImage') !== false) {
			$res = new EtiquetasController($request);
			$aParams['resource'] = $res->resource;
		}

		if (strpos($routeName, 'marcasImage') !== false) {
			$res = new MarcasController($request);
			$aParams['resource'] = $res->resource;
		}  

		if (strpos($routeName, 'productosImage') !== false) {
			$res = new ProductosController($request);
			$aParams['resource'] = $res->resource;
		}
		
		if (strpos($routeName, 'newsImage') !== false) {
			$res = new NewsController($request);
			$aParams['resource'] = $res->resource;
		}
		
		if (strpos($routeName, 'coloresImage') !== false) {
			$res = new ColoresController($request);
			$aParams['resource'] = $res->resource;
		}
		
		if (strpos($routeName, 'listasImage') !== false) {
			$res = new ListasController($request);
			$aParams['resource'] = $res->resource;
		}
		
		if (strpos($routeName, 'sliderImage') !== false) {
			$res = new SliderController($request);
			$aParams['resource'] = $res->resource;
		}
		
		if (strpos($routeName, 'pedidosClientesImage') !== false) {
			$res = new PedidosClientesController($request);
			$aParams['resource'] = $res->resource;
		}

		if (strpos($routeName, 'blogImage') !== false) {
			$res = new BlogController($request);
			$aParams['resource'] = $res->resource;
		}
		
		return $aParams;
		
	}


	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

	static function cargarImagen(Request $request)
	{

		$fileName = \time();
		$fileName .= '_' . \base64_encode($_FILES["file"]["name"]);
		$fileName .= '.jpg';


		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);
		$name = date('ymdHis') . '_' . rand(10000, 99999) . "." . $extension;

		$resource = new Image(
			[
				//'resource' => 'mailling',
				//'id_company' => 1,
				'imagen' =>  $name,
				'imagen_file' => $fileName,
				'habilitado' => 1,
			]
		);
		
		if (!$resource->save()) {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.dbError');
		}

		$ruta = \config('appCustom.UPLOADS_BE') .'mailling/'. $name;

		if (file_exists($ruta)) {
			unlink($ruta);
			if ($name && move_uploaded_file($_FILES["file"]["tmp_name"], $ruta)) { }
		} elseif ($name && move_uploaded_file($_FILES["file"]["tmp_name"], $ruta)) {
			$response['link'] = env('URL_BASE_UPLOADS') .'mailling/'. $name;
			echo stripslashes(json_encode($response));
		}
	}
    
}
