<?php
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use App\AppCustom\Util;
	use App\Http\Controllers\DashController;
	
	class DashUtilController extends Controller
	{
		public function __construct(DashController $res) {
			
			parent::__construct();
			
			$this->resource = $res->resource;
			$this->resourceLabel = $res->resourceLabel;
			$this->user = $res->user;
			$this->modelName = $res->modelName;
			$this->viewPrefix = $res->viewPrefix;
			
		}
		
		public function showMainView(Request $request, DashController $dashController) { 

			if ($this->user->hasAccess($this->resource . '.view')) {
				$aViewData = array();
				$aViewData['resource'] = $this->resource;
				$aViewData['resourceLabel'] = $this->resourceLabel;
				
				$request->request->set('d1',1);
				$aViewData['aData_d11'] = $dashController->index($request)->getData(true)['data'];
				$request->request->set('d1',2);
				$aViewData['aData_d12'] = $dashController->index($request)->getData(true)['data'];
				
				

				$response = 
					\Response::make(
						\View::make($this->viewPrefix . $this->resource . '.' . $this->resource)
							->with('aViewData', $aViewData)
					)
				;
			} else {
				$response = 
					\Response::make(
						\View::make('errors.unauthorized')
					)
				;
			}
			
			if (!\Cookie::get(\config('appCustom.cookieRestApiWeb'))) {

				$response->withCookie(
					\config('appCustom.cookieRestApiWeb'), 
					(\Sentinel::getUser()->email . ':' . \Sentinel::getUser()->api_token)
				)
				;
			}

			return $response;
		}
		
		
		
	}