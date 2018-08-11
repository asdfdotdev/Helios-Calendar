<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]
	
	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$errorMsg = '';
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(5,6);");
	if(!hasRows($result)){
		//$apiFail = true;
		//$errorMsg = 'Settings Table Corrupted.';
	} else {
		if(mysql_result($result,0,1) == '' || mysql_result($result,1,1) == ''){
			//$apiFail = true;
			//$errorMsg = 'Eventbrite API Settings Missing.';
		} else {
			$ip = gethostbyname("www.eventbrite.com");
			if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
				//$apiFail = true;
				//$errorMsg = 'Connection to Eventbrite Service Failed.';
			} else {
				$ebAPI = mysql_result($result,0,1);
				$ebUser = mysql_result($result,1,1);
				$ebID = (!isset($netID)) ? 0 : $netID;
				$ebSend = "/xml/event_update";

				$ebSend .= "?app_key=" . $ebAPI . "&user_key=" . $ebUser;
				$ebSend .= "&event_id=" . $ebID;
				
				$ebSend .= "&status=deleted";
				$ebSend .= "&privacy=0";

				$request = "GET " . $ebSend . " HTTP/1.0\r\nHost: www.eventbrite.com\r\nConnection: Close\r\n\r\n";
				fwrite($fp, $request);
				$data = '';
				while(!feof($fp)) {
					$data .= fread($fp,1024);
				}//end while
				fclose($fp);

				doQuery("UPDATE " . HC_TblPrefix . "eventnetwork SET IsActive = 0 WHERE NetworkID = '" . $ebID . "'");
			}//end if
		}//end if
	}//end if?>