<?php
/**
 * Description of Util
 *
 * @author martinm
 */

namespace App\AppCustom;


class Api {
	
	public $client;

	public function __construct() {
		$this->client = new ClientCustom(['base_uri' => env('URL_REST')]);
	}
}
