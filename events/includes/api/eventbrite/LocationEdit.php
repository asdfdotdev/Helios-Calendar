<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
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
	$errorMsg = '';
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(5,6);");
	if(!hasRows($result)){
		$apiFail = true;
		$errorMsg = 'Settings Table Corrupted.';
	} else {
		if(mysql_result($result,0,1) == '' || mysql_result($result,1,1) == ''){
			$apiFail = true;
			$errorMsg = 'Eventbrite API Settings Missing.';
		} else {
			$ebAPI = mysql_result($result,0,1);
			$ebUser = mysql_result($result,1,1);
			$ebID = (!isset($ebID)) ? 0 : $ebID;
			$ebSend = ($ebID == 0) ? "/xml/venue_new" : "/xml/venue_update";

			$ip = gethostbyname("www.eventbrite.com");
			if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
				$apiFail = true;
				$errorMsg = 'Connection to Eventbrite Service Failed.';
			} else {
				$ebSend .= "?app_key=" . $ebAPI;
				$ebSend .= "&user_key=" . $ebUser;
				$ebSend .= "&organizer_id=" . $hc_cfg62;
				
				if($ebID != ''){
					$ebSend .= "&id=" . $ebID;
				}//end if

				$ebSend .= ($lName != '') ? '&venue=' . urlencode($lName) : '';
				$ebSend .= ($address != '' ) ? '&address=' . urlencode($address) : '';
				$ebSend .= ($address2 != '' ) ? '&address2=' . urlencode($address2) : '';
				$ebSend .= ($city != '') ? "&city=" . urlencode($city) : '';
				$ebSend .= ($state != '') ? '&region=' . urlencode($state) : '';
				$ebSend .= ($zip != '') ? '&postal_code=' . urlencode($zip) : '';
				$ebSend .= ($country_code != '') ? '&country_code=' . urlencode($country_code) : '';
				
				$request = "GET " . $ebSend . " HTTP/1.0\r\nHost: www.eventbrite.com\r\nConnection: Close\r\n\r\n";
				fwrite($fp, $request);
				$data = '';
				while(!feof($fp)) {
					$data .= fread($fp,1024);
				}//end while
				fclose($fp);

				$fetched = xml2array($data);
				if($fetched[0]['name'] == 'error'){
					$apiFail = true;
					$errorMsg = 'Error Msg From Eventbrite - <i>' . $fetched[0]['elements'][0]['value'] . '</i>';
				} else {
					$stopEB = count($fetched[0]['elements']);
					for($x=0;$x<$stopEB;$x++){
						if($fetched[0]['elements'][$x]['name'] == 'id'){
							$ebID = $fetched[0]['elements'][$x]['value'];
							break;
						}//end if
					}//end for
				}//end if
			}//end if
			echo ($errorMsg != '') ? $errorMsg : '';
		}//end if
	}//end if?>