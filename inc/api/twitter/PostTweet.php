<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include_once(HCPATH.HCINC.'/functions/oauth.php');
	
	$errorMsg = '';
	$prefix = 'https://';
	$site = 'api.twitter.com';
	$path = '/statuses/update.xml';
	
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
		//	The following line of code, which sets the User-Agent for the API request, cannot be edited. Modifing this line of code violates the Helios Calendar SLA.
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
		
		$error = xml_elements('error',$read);
		if($error[0] != ''){
			$apiFail = true;
			$errorMsg = 'Error message from Twitter: <i>' . $error[0] . '</i>';
		} else {
			if(xml_tag_value('id', $read) != ""){
				$tweetID = xml_tag_value('id', $read);
			}
		}
		
	}
	echo ($errorMsg != '') ? $errorMsg : '';
?>