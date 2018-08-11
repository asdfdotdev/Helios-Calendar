<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Generate signed request string for oAuth 1.x Requests (Used by Twitter)
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param array $array Request parameters array, will be sorted and used to build request signature.
	 * @param string $url API URL, Ex: https://api.twitter.com/statuses/update.xml
	 * @param string $secret consumer and token secret string, used to sign the request.
	 * @param binary $method Request method, 0 = GET, 1 = POST (Default:0)
	 * @return string signed request string.
	 */
	function oauth1_sign_request($array,$url,$secret,$method = 0){
		uksort($array, 'strcmp');
		$method = ($method == 1) ? "POST" : "GET";
		
		foreach($array as $parameter => $value) {
			if(is_array($value)) {
				natsort($value);
				foreach ($value as $duplicate_value)
					$prmSorted[] = $parameter . '=' . $duplicate_value;
			} else {
				$prmSorted[] = $parameter . '=' . $value;
			}
		}
		$prmString = implode('&',$prmSorted);
		$sigString = $method.'&'.rawurlencode($url).'&'.rawurlencode($prmString);
		
		return base64_encode(hash_hmac('sha1',$sigString,$secret,true));
	}
?>
