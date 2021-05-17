<?php
namespace App\Http\Controllers;
use App\AppCustom\Util;

class Banners2UtilController extends GenericUtilController
{
    public function __construct(Banners2Controller $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->user = $res->user;
		$this->modelName = $res->modelName;
		$this->viewPrefix = $res->viewPrefix;
		$this->itemNameField = 'nombre';
	}

	public function showMainViewPersonasRelated($id) {

		$aResult = Util::getDefaultArrayResult();

		if ($item = \App\AppCustom\Models\MktListas::find($id)) {
			if ($this->user->hasAccess($this->resource . '.view')) {
				
				$aViewData['item'] = $item;
				$aViewData['resource'] = $this->resource;
				$aViewData['resourceLabel'] = $this->resourceLabel;
				$aViewData['itemNameField'] = $this->itemNameField;
				$aResult['html'] = 
					\View::make('banners2.banners2.banners2PersonasRelatedMain')
						->with('aViewData', $aViewData)
						->render()
					;
			} else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
		} else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.itemNotFound');
		}

		return response()->json($aResult);
	}
}