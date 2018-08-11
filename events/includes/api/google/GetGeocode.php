<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$errorMsg = $lat = $lon = '';
	if($hc_cfg26 == ''){
		$errorMsg = 'Google Maps API Key Missing.';
	} else {
		$ip = gethostbyname('maps.google.com');
		if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
			$errorMsg = 'Connection to Google Maps Service Failed.';
		} else {
			$googSend = "/maps/geo?output=csv&key=" . $hc_cfg26 . "&q=" . urlencode(cIn($address) . ", " . cIn($city) . ", " . cIn($state) . " " . cIn($zip) . " " . cIn($country));

			$request = "GET $googSend HTTP/1.1\r\n";
			$request .= "Host: maps.google.com\r\n";
			$request .= "Connection: Close\r\n\r\n";
			
			fwrite($fp, $request);
			$data = '';
			while(!feof($fp)) {
				$data .= fread($fp,1024);
			}//end while
			fclose($fp);

			$regResponse = '/[0-9]*,[0-9]*,[-]?[0-9]*.[0-9]*,[-]?[0-9]*.[0-9]*/';	
			preg_match_all($regResponse, $data, $fetched);
			$fetched = explode(',',$fetched[0][0]);
			switch($fetched[0]){
				case 200:
					if(is_numeric($fetched[2]) && is_numeric($fetched[3])){
						$lat = $fetched[2];
						$lon = $fetched[3];
					}//end if
					break;
				default:
					$errorMsg = 'Geocode Retrival Failed - Error Code ' . $fetched[0];
					break;
			}//end if

			echo ($errorMsg != '') ? $errorMsg : '';
		}//end if
	}//end if?>
