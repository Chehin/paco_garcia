<?php

namespace App\Http\Controllers\Fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\EmailTracking;
use App\AppCustom\Models\LinkTracking;
use App\Http\Controllers\Controller; 

class TrackingController extends Controller
{
	public function trackingMail(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();

        $resource = new EmailTracking(
            [
            
                'campaign_id' => $request->input('id'),
                'id_user' => $request->input('user'),
                'campaign_testing_id' =>$request->input('idT'),
                'id_ab' =>$request->input('idab'),
            ]
        )
        ;
        if (!$resource->save()) {
        $aResult['status'] = 1;
        $aResult['msg'] = \config('appCustom.messages.dbError');
        }
        return response()->json($aResult);
    }

    public function trackingLink(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();

        $resource = new LinkTracking(
            [            
                'campaign_id' => $request->input('id'),
                'id_user' => $request->input('user'),
                'campaign_testing_id' =>$request->input('idT'),
                'id_ab' =>$request->input('idab'),
                'link' =>$request->input('link'),
            ]
        )
        ;
        if (!$resource->save()) {
        $aResult['status'] = 1;
        $aResult['msg'] = \config('appCustom.messages.dbError');
        }
        return response()->json($aResult);
    }
}