<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
/*
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modification of the source code within this file is prohibited.	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	$site = 'api.twitter.com';
	$path = '/oauth/request_token';

	$consumer_key = "nvnfYtmUy75LPARKAnWNmg";
	$consumer_secret = "9YMJptcCE1s0rlT494qIgULobTXfKvUJgh9eKSjOk";
	$token_key = $token_secret = $strEncode = $strHeader = '';
	$sigSecret = rawurlencode($consumer_secret) . "&" . rawurlencode($token_key);
	$timestamp = date("U");
	$nonce = md5($_SESSION['AdminEmail'] . $timestamp);

	$paramsRaw = array('oauth_token'			=>	'',
				'oauth_consumer_key'	=>	$consumer_key,
				'oauth_nonce'			=>	$nonce,
				'oauth_signature_method'	=>	'HMAC-SHA1',
				'oauth_timestamp'		=>	$timestamp,
				'oauth_token'			=>	'',
				'oauth_version'		=>	'1.0');
	uksort($paramsRaw, 'strcmp');

	foreach($paramsRaw as $parameter => $value) {
		if(is_array($value)) {
			natsort($value);
			foreach ($value as $duplicate_value)
				$prmSorted[] = $parameter . '=' . $duplicate_value;
		} else {
			$prmSorted[] = $parameter . '=' . $value;
		}
	}
	$prmString = implode('&',$prmSorted);
	$sigString = 'GET&' . rawurlencode('http://' . $site . $path) . '&' . rawurlencode($prmString);
	$sigSigned = base64_encode(hash_hmac('sha1',$sigString,$sigSecret,true));
	
	$ip = gethostbyname($site);
	if($fp = fsockopen($ip, 80, $errno, $errstr, 1)){
		$read = "";
		$request = "GET $path HTTP/1.1\r\n";
		$request .= "Host: $site:80 \r\n";
		$request .= 'Authorization: OAuth realm="http://' . $site . $path . '",
								oauth_consumer_key="' . $paramsRaw['oauth_consumer_key'] . '",
								oauth_token="' . $paramsRaw['oauth_token'] . '",
								oauth_nonce="' . $paramsRaw['oauth_nonce'] . '",
								oauth_timestamp="' . $paramsRaw['oauth_timestamp'] . '",
								oauth_signature_method="' . $paramsRaw['oauth_signature_method'] . '",
								oauth_version="' . $paramsRaw['oauth_version'] . '",
								oauth_signature="' . rawurlencode($sigSigned) . '"' . "\r\n";
		$request .= "Connection: Close\r\n\r\n";
		fwrite($fp, $request);

		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}
		fclose($fp);

		$regResponse = '/[A-z|a-z]*[_][A-z|a-z]*[=][A-Z|a-z|0-9|-]*/';
		preg_match_all($regResponse, $read, $fetched);
		$token = array();
		foreach($fetched[0] as $val)
			$token[] = explode("=", $val);
		
		foreach($token as $val){
			switch($val[0]){
				case 'oauth_token':
					$_SESSION['RequestToken'] = $val[1];
					break;
				case 'oauth_token_secret':
					$_SESSION['RequestSecret'] = $val[1];
					break;
			}
		}
	}
?>