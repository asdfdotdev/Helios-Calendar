<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include_once(HCPATH.HCINC.'/functions/oauth.php');
	
	$errorMsg = '';
	$prefix = 'https://';
	$site = 'api.twitter.com';
	$path = '/oauth/access_token';
	
	if(!$fp = fsockopen("ssl://$site", 443, $errno, $errstr, 20)){
		$prefix = 'http://';
		$fp = fsockopen("$site", 80, $errno, $errstr, 20);
	}
	
	$sigSecret = rawurlencode($consumer_secret)."&".rawurlencode($oauth_token);
	$timestamp = date("U");
	$nonce = md5(mt_rand().$timestamp);
	$paramsRaw = array('oauth_token'			=>	$oauth_token,
					'oauth_consumer_key'	=>	$consumer_key,
					'oauth_nonce'			=>	$nonce,
					'oauth_signature_method'	=>	'HMAC-SHA1',
					'oauth_timestamp'		=>	$timestamp,
					'oauth_verifier'		=>	$oauth_verifier,
					'oauth_version'		=>	'1.0');
	$sigSigned = oauth1_sign_request($paramsRaw,$prefix.$site.$path,$sigSecret);
	
	if(!$fp){
		$apiFail = true;
		$errorMsg = 'Connection to Twitter Service Failed.';
	} else {
		$read = "";
		$request = "GET $path HTTP/1.1\r\n";
		$request .= "User-Agent: Helios Calendar ".HCVersion." \r\n";
		$request .= "Host: $site \r\n";
		$request .= "Accept: */* \r\n";
		$request .= 'Authorization: OAuth oauth_consumer_key="' . $paramsRaw['oauth_consumer_key'] . '",
								oauth_token="' . $paramsRaw['oauth_token'] . '",
								oauth_nonce="' . $paramsRaw['oauth_nonce'] . '",
								oauth_timestamp="' . $paramsRaw['oauth_timestamp'] . '",
								oauth_signature_method="' . $paramsRaw['oauth_signature_method'] . '",
								oauth_verifier="' . $paramsRaw['oauth_verifier'] . '",
								oauth_version="' . $paramsRaw['oauth_version'] . '",
								oauth_signature="' . rawurlencode($sigSigned) . '"' . "\r\n";
		$request .= "Connection: Close\r\n\r\n";
		
		fwrite($fp, $request);
		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}
		fclose($fp);

		$regResponse = '/[A-z|0-9]*_[A-z|0-9]*=[A-z|0-9|.|-]*/';
		preg_match_all($regResponse, $read, $fetched);
		$token = array();
		foreach($fetched[0] as $val)
			$token[] = explode("=", $val);

		foreach($token as $val){
			switch($val[0]){
				case 'oauth_token':
					$authToken = cIn(strip_tags($val[1]));
					break;
				case 'oauth_token_secret':
					$authSecret = cIn(strip_tags($val[1]));
					break;
				case 'user_id':
					$authUserID = cIn(strip_tags($val[1]));
					break;
				case 'screen_name':
					$authUser = cIn(strip_tags($val[1]));
					break;
			}
		}

		
	}
//	echo ($errorMsg != '') ? $errorMsg : '';
	
	unset($_SESSION['RequestToken']);
	unset($_SESSION['RequestSecret']);
?>