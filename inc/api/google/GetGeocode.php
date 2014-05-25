<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	$errorMsg = $lat = $lon = '';
	if(!$fp = fsockopen("ssl://maps.googleapis.com", 443, $errno, $errstr, 20))
		$fp = fsockopen("maps.googleapis.com", 80, $errno, $errstr, 20);
	
	if(!$fp){
		$apiFail = true;
		$errorMsg = 'Connection to Google Failed.';
	} else {
		$locString = (isset($locString)) ? clean_accents($locString) : '';
		
		$gSend = "maps.googleapis.com/maps/api/geocode/xml?address=" . urlencode($locString) . "&sensor=false";
		$data = '';
		$request = "GET " . $gSend . " HTTP/1.1\r\nHost: maps.googleapis.com\r\nConnection: Close\r\n\r\n";
		
		fwrite($fp, $request);
		while(!feof($fp)) {
			$data .= fread($fp,1024);
		}
		fclose($fp);
		
		$tags = xml_elements('location', $data);
		if($tags[0] == ''){
			$apiFail = true;
			$errorMsg = 'Geocode Retrieval Failed.';
		} else {
			$lat = xml_tag_value('lat', $tags[0]);
			$lon = xml_tag_value('lng', $tags[0]);
		}
	}
	echo ($errorMsg != '') ? $errorMsg : '';
?>