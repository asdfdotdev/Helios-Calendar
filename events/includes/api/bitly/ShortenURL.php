<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$errorMsg = '';
	$result = doQuery("SELECT ShortURL FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "'");
	if(hasRows($result) && mysql_result($result,0,0) != ''){
		$shortLink = (strpos(mysql_result($result,0,0),"http://") !== false) ? mysql_result($result,0,0) : $shortLink;
	} else {
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(57,58)");
		if(!hasRows($result)){
			$errorMsg = 'Settings Table Corrupted.';
		} else {
			if(mysql_result($result,0,0) == '' && mysql_result($result,1,0) == ''){
				$errorMsg = 'bit.ly API Settings Missing.';
			} else {
				$bitlyUser = mysql_result($result,0,0);
				$bitlyAPI = mysql_result($result,1,0);
				
				$ip = gethostbyname("api.bit.ly");
				if(!$fp = fsockopen($ip, 80, $errno, $errstr, 1)){
					$errorMsg = 'Connection to bit.ly Service Failed.';
				} else {
					$btlySend = "/shorten?version=2.0.1&format=xml&login=" . $bitlyUser . "&apiKey=" . $bitlyAPI . "&longUrl=" . urlencode($shortLink);

					$request = "GET $btlySend HTTP/1.1\r\n";
					$request .= "Host: api.bit.ly\r\n";
					$request .= "Connection: Close\r\n\r\n";

					fwrite($fp, $request);
					$data = '';
					while(!feof($fp)) {
						$data .= fread($fp,1024);
					}//end while
					fclose($fp);

					$fetched = xml2array($data);
					if(isset($fetched[0]['elements'][1]['value'])){
						$errorMsg = 'Error Msg From bit.ly - <i>' . $fetched[0]['elements'][1]['value'] . '</i>';
					} else {
						$bitURL = '';
						$stop = count($fetched[0]['elements'][2]['elements'][0]['elements']);
						for($x=0;$x<$stop;$x++){
							if($fetched[0]['elements'][2]['elements'][0]['elements'][$x]['name'] == 'shortUrl'){
								$bitURL = $fetched[0]['elements'][2]['elements'][0]['elements'][$x]['value'];
								break;
							}//end if
						}//end for

						if($bitURL != ''){
							doQuery("UPDATE " . HC_TblPrefix . "events SET ShortURL = '" . cIn($bitURL) . "' WHERE PkID = '" . $eID . "'");
							$shortLink = $bitURL;
						}//end if
					}//end if
				}//end if
				//echo ($errorMsg != '') ? $errorMsg : '';
			}//end if
		}//end if
	}//end if?>