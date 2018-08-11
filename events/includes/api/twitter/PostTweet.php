<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]
	
	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$errorMsg = '';
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(46,47);");
	$twUser = mysql_result($result,0,0);
	$twPass = mysql_result($result,1,0);
	$showStatus = (isset($showStatus)) ? $showStatus : 0;
	$tweetID = $userID = '';

	if($twtrMsg != ''){	
		$ip = gethostbyname("www.twitter.com");
		if(($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
			/*	The line of code following this Comment may not be altered. Removing Helios Calendar as
			 	your tweets source application is not permitted under the terms of the Helios Calendar SLA		*/
			$request = "POST /statuses/update.xml?source=helioscalendar&status=" . urlencode($twtrMsg) . " HTTP/1.0\r\n";
			$request .= "Host: www.twitter.com\r\n";
			$request .= "Authorization: Basic " . base64_encode($twUser . ':' . $twPass) . "\r\n";
			$request .= "Connection: Close\r\n\r\n";

			fwrite($fp, $request);
			$data = '';
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
					
					$tweetID = $userID . '/status/' . $tweetID;
				}//end if
			}//end if
		}//end if
		echo ($errorMsg != '' && $showStatus > 1) ? $errorMsg : '';
	}//end if?>