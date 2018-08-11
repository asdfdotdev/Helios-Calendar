<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$errorMsg = '';
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
	if(!hasRows($result)){
		//$apiFail = true;
		//$errorMsg = 'Settings Table Corrupted.';
	} else {
		if(mysql_result($result,0,1) == '' || mysql_result($result,1,1) == '' || mysql_result($result,2,1) == ''){
			//$apiFail = true;
			//$errorMsg = 'Eventful API Settings Missing.';
		} else {
			$ip = gethostbyname("api.evdb.com");
			if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
				//$apiFail = true;
				//$errorMsg = 'Connection to Eventful Service Failed.';
			} else {
				$efKey = mysql_result($result,0,1);
				$efUser = mysql_result($result,1,1);
				$efPass = mysql_result($result,2,1);
				$efSig = mysql_result($result,3,1);
				$efID = (!isset($netID)) ? 0 : $netID;
				$efSend = "/rest/venues/withdraw";

				$efSend .= "?app_key=" . $efKey . "&user=" . $efUser . "&password=" . urlencode($efPass);
				$efSend .= "&id=" . $efID;

				$request = "GET " . $efSend . " HTTP/1.1\r\n";
				$request .= "Host: api.evdb.com\r\n";
				$request .= "Connection: Close\r\n\r\n";

				fwrite($fp, $request);
				$data = '';
				while(!feof($fp)) {
					$data .= fread($fp,1024);
				}//end while
				fclose($fp);

				doQuery("UPDATE " . HC_TblPrefix . "locationnetwork SET IsActive = 0 WHERE NetworkID = '" . $efID . "' AND NetworkType = 1");
			}//end if
		}//end if
	}//end if?>
