<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include_once(HCPATH.HCINC.'/functions/oauth.php');
		
	$errorMsg = '';
	$prefix = 'https://';
	$site = 'api.twitter.com';
	$path = '/1.1/statuses/update.json';
	
	if(!$fp = fsockopen("ssl://$site", 443, $errno, $errstr, 20)){
		$prefix = 'http://';
		$fp = fsockopen("$site", 80, $errno, $errstr, 20);
	}
	
	$sigSecret = rawurlencode($consumer_secret)."&".rawurlencode($oauth_secret);
	$timestamp = date("U");
	$nonce = md5($_SESSION['AdminEmail'].$timestamp); 
	$paramsRaw = array('oauth_token'			=>	$oauth_token,
					'oauth_consumer_key'	=>	$consumer_key,
					'oauth_nonce'			=>	$nonce,
					'oauth_signature_method'	=>	'HMAC-SHA1',
					'oauth_timestamp'		=>	$timestamp,
					'status'				=>	rawurlencode(utf8_encode($twtrMsg)),
					'oauth_version'		=>	'1.0');
	$sigSigned = oauth1_sign_request($paramsRaw,$prefix.$site.$path,$sigSecret,1);
	
	if(!$fp){
		$apiFail = true;
		$errorMsg = 'Connection to Twitter Service Failed.';
	} else {
		$read = "";
		$request = "POST $path?status=" . $paramsRaw['status'] . " HTTP/1.1\r\n";
		$request .= "User-Agent: Helios Calendar ".HCVersion." \r\n";
		$request .= "Host: $site \r\n";
		$request .= "Accept: */* \r\n";
		$request .= 'Authorization: OAuth oauth_consumer_key="' . $paramsRaw['oauth_consumer_key'] . '",
								oauth_nonce="' . $paramsRaw['oauth_nonce'] . '",
								oauth_signature="' . rawurlencode($sigSigned) . '",
								oauth_signature_method="' . $paramsRaw['oauth_signature_method'] . '",
								oauth_timestamp="' . $paramsRaw['oauth_timestamp'] . '",
								oauth_token="' . $paramsRaw['oauth_token'] . '",
								oauth_version="' . $paramsRaw['oauth_version'] . '"' . "\r\n";
		$request .= "Connection: Close\r\n\r\n";
		
		fwrite($fp, $request);
		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}
		fclose($fp);

		$api_response = explode("\n", $read);
		$api_response = end($api_response);
		$api_response = json_decode(preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', $api_response), false);

		if(isset($api_response->errors)){
			$apiFail = true;
			$api_error = $api_response->errors[0];
			$errorMsg = 'Error message from Twitter: <i>' . $api_error->message . '</i>';
		} else {
			if($api_response->id_str != ''){
				$tweetID = $api_response->id_str;
			}
		}		
	}
	echo ($errorMsg != '') ? $errorMsg : '';
?>