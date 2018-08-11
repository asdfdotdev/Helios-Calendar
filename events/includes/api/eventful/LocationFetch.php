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
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
	if(!hasRows($result)){
		$apiFail = true;
		$errorMsg = 'Settings Table Corrupted.';
	} else {
		if(mysql_result($result,0,1) == '' || mysql_result($result,1,1) == '' || mysql_result($result,2,1) == ''){
			$apiFail = true;
			$errorMsg = 'Eventful API Settings Missing.';
		} else {
			$efKey = mysql_result($result,0,1);
			$efUser = mysql_result($result,1,1);
			$efPass = base64_decode(mysql_result($result,2,1));
			$efSig = mysql_result($result,3,1);
			$efID = (!isset($efID)) ? 0 : $efID;
			$efSend = "/rest/venues/search";
			
			$ip = gethostbyname("api.evdb.com");
			if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
				$apiFail = true;
				$errorMsg = 'Connection to Eventful Service Failed.';
			} else {
				$efSend .= "?app_key=" . $efKey . "&user=" . $efUser . "&password=" . $efPass;
				$efSend .= "&keywords=" . urlencode($lName);
				$efSend .= "&location=" . urlencode($city . ', ' . $state);

				$request = "GET " . $efSend . " HTTP/1.0\r\nHost: api.evdb.com\r\nConnection: Close\r\n\r\n";
				fwrite($fp, $request);
				$data = '';
				while(!feof($fp)) {
					$data .= fread($fp,1024);
				}//end while
				fclose($fp);

				$fetched = xml2array($data);
				if(!isset($fetched[0]['elements'][0]['value']) || $fetched[0]['elements'][0]['value'] == 0){
					//$apiFail = true;
					//$errorMsg = 'Error Msg From Eventful - <i>Venue Not Found</i>';
				} else if(isset($fetched[0]['elements'][8]['elements'][0]['attributes']['id'])){
					$efFetched = $fetched[0]['elements'][8]['elements'][0]['attributes']['id'];

					if($efFetched != ''){
						doQuery("INSERT INTO " . HC_TblPrefix . "locationnetwork(LocationID,NetworkID,NetworkType,IsDownload,IsActive)
								VALUES('" . $lID . "','" . cIn($efFetched) . "',1,1,1);");
					}//end if
				}//end if
			}//end if
			echo ($errorMsg != '') ? $errorMsg : '';
		}//end if
	}//end if?>