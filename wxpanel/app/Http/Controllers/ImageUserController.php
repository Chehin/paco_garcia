<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Sentinel\User;
use Sentinel;

class ImageUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
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
        
		
		$resource = Sentinel::getUser();

		if (!empty($resource->image)) {
			@unlink(\config('appCustom.UPLOADS_BE_USER') . $resource->image);
		}

		$fileName = '';

		if (!empty(\trim($request->input('image-data')))) {

			$fileName = \time();
			$fileName .= '_' . $resource->id;
			$fileName .= '.png';

			//$path, $fileName, $base64File, $thumbProportion
			Util::uploadBase64File(
				\config('appCustom.UPLOADS_BE_USER'),
				$fileName, 
				$request->input('image-data'),
				0.2
			)
			;
		}

		$resource->image = $fileName;


		if (!$resource->save()) {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.dbError');
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
		
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		
	
	}
}
