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
	
	$errorMsg = $lat = $lon = '';
	$ip = gethostbyname('maps.google.com');
	if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
		$errorMsg = 'Connection to Google Maps Service Failed.';
	} else {
		$locString = utf8_encode($locString);
		$googSend = "/maps/geo?output=csv&q=" . urlencode($locString);

		$request = "GET $googSend HTTP/1.1\r\n";
		$request .= "Host: maps.google.com\r\n";
		$request .= "Connection: Close\r\n\r\n";

		fwrite($fp, $request);
		$data = '';
		while(!feof($fp)) {
			$data .= fread($fp,1024);
		}
		fclose($fp);

		$regResponse = '/[0-9]*,[0-9]*,[-]?[0-9]*.[0-9]*,[-]?[0-9]*.[0-9]*/';	
		preg_match_all($regResponse, $data, $fetched);
		$fetched = explode(',',$fetched[0][0]);
		switch($fetched[0]){
			case 200:
				if(is_numeric($fetched[2]) && is_numeric($fetched[3])){
					$lat = $fetched[2];
					$lon = $fetched[3];
				}
				break;
			default:
				//	Uncomment Next Line to Debug
				//$errorMsg = 'Geocode Retrival Failed - Error Code ' . $fetched[0];
				break;
		}

		echo ($errorMsg != '') ? $errorMsg : '';
	}
?>