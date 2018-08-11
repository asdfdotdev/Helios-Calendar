<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html

	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code contained in this file is 	|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice.	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	if(!isset($hc_cfg00) || !isset($_SESSION[$hc_cfg00 .'AdminLoggedIn'])){
		exit();
	}//end if

	$apiFail = false;
	$showStatus = 2;
	$site = 'api.twitter.com';
	$path = '/oauth/access_token';

	$consumer_key = "nvnfYtmUy75LPARKAnWNmg";
	$consumer_secret = "9YMJptcCE1s0rlT494qIgULobTXfKvUJgh9eKSjOk";
	$token_key = $_SESSION[$hc_cfg00 . 'RequestToken'];
	$token_secret = $_SESSION[$hc_cfg00 . 'RequestSecret'];
	$strEncode = $strHeader = '';
	$sigSecret = rawurlencode($consumer_secret) . "&" . rawurlencode($token_key);
	$timestamp = date("U");
	$nonce = md5($_SESSION[$hc_cfg00 . 'AdminEmail'] . $timestamp);

	$paramsRaw = array('oauth_token'		=>	$token_key,
				'oauth_consumer_key'	=>	$consumer_key,
				'oauth_nonce'			=>	$nonce,
				'oauth_signature_method'	=>	'HMAC-SHA1',
				'oauth_timestamp'		=>	$timestamp,
				'oauth_verifier'		=>	$twtrPin,
				'oauth_version'		=>	'1.0');
	uksort($paramsRaw, 'strcmp');
	
	foreach($paramsRaw as $parameter => $value) {
		if(is_array($value)) {
			natsort($value);
			foreach ($value as $duplicate_value) {
				$prmSorted[] = $parameter . '=' . $duplicate_value;
			}//end foreach
		} else {
			$prmSorted[] = $parameter . '=' . $value;
		}//end if
	}//end foreach
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
								oauth_verifier="' . $paramsRaw['oauth_verifier'] . '",
								oauth_version="' . $paramsRaw['oauth_version'] . '",
								oauth_signature="' . rawurlencode($sigSigned) . '"' . "\r\n";
		$request .= "Connection: Close\r\n\r\n";
		fwrite($fp, $request);

		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}//end while
		fclose($fp);

		$regResponse = '/[A-z|a-z]*[_][A-z|a-z]*[=][A-Z|a-z|0-9|-|_]*/';
		preg_match_all($regResponse, $read, $fetched);
		$token = array();
		foreach($fetched[0] as $val){
			$token[] = explode("=", $val);
		}//end foreach


		foreach($token as $val){
			switch($val[0]){
				case 'oauth_token':
					$authToken = $val[1];
					break;
				case 'oauth_token_secret':
					$authSecret = $val[1];
					break;
				case 'user_id':
					$authUserID = $val[1];
					break;
				case 'screen_name':
					$authUser = $val[1];
					break;
			}//end switch
		}//end foreach

		if($authToken == ''){
			$apiFail = true;
			$errorMsg = 'Error Msg From Twitter - <i>Authorization Failed</i> - Please create a new authorization Pin and try again.';
		}//end if
		echo ($errorMsg != '' && $showStatus > 1) ? $errorMsg : '';
	}//end if?>