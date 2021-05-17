<?php
/**
 * Description of Util
 *
 * @author martinm
 */

namespace App\AppCustom;

use GuzzleHttp\Client;

class ClientCustom extends Client {
	
	public function resJson($method, $uri) {
		$response = $this->request($method, $uri);
		
		if (200 === $response->getStatusCode()){
            return json_decode($response->getBody(), true);
        }
	}
	
    
}
