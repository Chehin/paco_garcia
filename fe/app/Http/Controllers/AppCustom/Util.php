<?php
/**
 * Description of Util
 *
 * @author martinm
 */

namespace App\AppCustom;


class Util {
    
    
    static function aResult() {
        return [
            'status' => 0,
            'msg'    => 'ok',
			'value' => '',
            'html'  => '',
            'data'   => [],
        ];
    }
  
    static function orderString($string) {
        $stringParts = str_split($string);
        sort($stringParts);
        return implode('', $stringParts);
    }
    
	
	static function truncateString($string,$length=100,$append="&hellip;") {
        $string = \trim($string);

        if(strlen($string) > $length) {
          $string = \wordwrap($string, $length);
          $string = \explode("\n", $string, 2);
          $string = $string[0] . $append;
        }

        return $string;
    }
	
	
	
	static function getSomeString($modelName, $field, $strSize = 25) {
		
        do {
            $str = \str_random($strSize);
        } while ($modelName::where($field, "=", $str)->first() instanceof $modelName);
        
        return $str;
        
    }
	
	
	static function getSubdomain($url) {

		$parsedUrl = parse_url($url);

		$host = explode('.', $parsedUrl['host']);
		
		if (count($host) > 1) {
			$subdomain = $host[0];
		
			return  $subdomain;
		}

		
	}
	
	static function dateOk($date, $format = 'd/m/Y') {
		$d = \DateTime::createFromFormat($format, $date);
		
		return $d && $d->format($format) === $date;
	}
	
	public static function in_array($aArray, $field, $value) {
		if ($aArray) {
			foreach ($aArray as $item) {
				if ($item[$field] == $value) {
					return $item;
				}
			}
		}
	}
	
	
    
}
