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
	$errorMsg = '';
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(46,47);");
	$token_key = mysql_result($result,0,0);
	$token_secret = mysql_result($result,1,0);

	if($twtrMsg != ''){
		$site = 'api.twitter.com';
		$path = '/statuses/update.xml';

		$consumer_key = "nvnfYtmUy75LPARKAnWNmg";
		$consumer_secret = "9YMJptcCE1s0rlT494qIgULobTXfKvUJgh9eKSjOk";
		$strEncode = $strHeader = '';
		$sigSecret = rawurlencode($consumer_secret) . "&" . rawurlencode($token_secret);
		$timestamp = date("U");
		$nonce = md5($_SESSION[$hc_cfg00 . 'AdminEmail'] . $timestamp);

		$paramsRaw = array('oauth_token'		=>	$token_key,
					'oauth_consumer_key'	=>	$consumer_key,
					'oauth_nonce'			=>	$nonce,
					'oauth_signature_method'	=>	'HMAC-SHA1',
					'oauth_timestamp'		=>	$timestamp,
					'status'				=>	rawurlencode(utf8_ninjadecode($twtrMsg)),
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
		$sigString = 'POST&' . rawurlencode('http://' . $site . $path) . '&' . rawurlencode($prmString);
		$sigSigned = base64_encode(hash_hmac('sha1',$sigString,$sigSecret,true));

		$ip = gethostbyname($site);
		if($fp = fsockopen($ip, 80, $errno, $errstr, 1)){
			$data = "";
			$request = "POST $path?status=" . $paramsRaw['status'] . " HTTP/1.1\r\n";
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

			while(!feof($fp)) {
				$data .= fread($fp,1024);
			}//end while
			fclose($fp);

			if($showStatus > 0){
				$fetched = xml2array($data);
				if($fetched[0]['name'] == 'error'){
					$apiFail = true;
					$errorMsg = 'Error Msg From Twitter - <i>' . $fetched[0]['elements'][0]['value'] . '</i>';
				} else {
					$stop = count($fetched[0]['elements']);
					for($i=0;$i < $stop;$i++){
						if($fetched[0]['elements'][0]['elements'][$i]['name'] == 'id'){
							$tweetID = $fetched[0]['elements'][0]['elements'][$i]['value'];
						}else if($fetched[0]['elements'][0]['elements'][$i]['name'] == 'screen_name'){
							$userID = $fetched[0]['elements'][0]['elements'][$i]['value'];
						}//end if
					}//end for
				}//end if
			}//end if
		}//end if
		echo ($errorMsg != '' && $showStatus > 1) ? $errorMsg : '';
	}//end if