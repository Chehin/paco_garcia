<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\ServiceController;
use App\AppCustom\Models\Image;

class ProductosRelatedUtilController extends Controller
{
	
	static function getParameters(Request $request) {
		$routeName = $request->route()->getName();
		
		$aParams = [];
		if (strpos($routeName, 'workNoteRelated') !== false) {
			$res = new WorkController($request);
			$aParams['resource'] = $res->resource;
			$aParams['idEdicion'] = $res->filterNote;
		} elseif (strpos($routeName, 'serviceNoteRelated') !== false) {
			$res = new ServiceController($request);
			$aParams['resource'] = $res->resource;
			$aParams['idEdicion'] = $res->filterNote;
		} elseif (strpos($routeName, 'productNoteRelated') !== false) {
			$res = new ProductController($request);
			$aParams['resource'] = $res->resource;
			$aParams['idEdicion'] = $res->filterNote;
		} elseif (strpos($routeName, 'newsNoteRelated') !== false) {
			$res = new NewsController($request);
			$aParams['resource'] = $res->resource;
			$aParams['idEdicion'] = $res->filterNote;
		} elseif (strpos($routeName, 'companyNoteRelated') !== false) {
			$res = new CompanyController($request);
			$aParams['resource'] = $res->resource;
			$aParams['idEdicion'] = $res->filterNote;
		} elseif (strpos($routeName, 'sliderNoteRelated') !== false) {
			$res = new SliderController($request);
			$aParams['resource'] = $res->resource;
			$aParams['idEdicion'] = $res->filterNote;
		} elseif (strpos($routeName, 'productosProductosRelated') !== false) {
			$res = new ProductosController($request);
			$aParams['resource'] = $res->resource;
			$aParams['idEdicion'] = $res->filterNote;
		}
		
		return $aParams;
		
	}


	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
}
