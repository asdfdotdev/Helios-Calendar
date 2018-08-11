<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	$errorMsg = '';
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(5,6);");
	if(!hasRows($result)){
		$apiFail = true;
		$errorMsg = 'Eventbrite API Settings Unavailable.';
	} else {
		$ebAPI = cOut(mysql_result($result,0,1));
		$ebUser = cOut(mysql_result($result,1,1));
		
		if($ebAPI == '' || $ebUser == ''){
			$apiFail = true;
			$errorMsg = 'Eventbrite API Settings Missing.';
		} else {
			$ebID = (!isset($netID)) ? 0 : $netID;
			$ebSend = "/xml/event_update?app_key=".$ebAPI."&user_key=".$ebUser;
			$ebSend .= "&event_id=" . $ebID;
			$ebSend .= "&status=deleted";
			$ebSend .= "&privacy=0";
			
			if(!$fp = fsockopen("ssl://www.eventbrite.com", 443, $errno, $errstr, 20))
				$fp = fsockopen("www.eventbrite.com", 80, $errno, $errstr, 20);

			if(!$fp){
				$apiFail = true;
				$errorMsg = 'Connection to Eventbrite Service Failed.';
			} else {
				$data = '';
				$request = "GET " . $ebSend . " HTTP/1.1\r\nHost: www.eventbrite.com\r\nConnection: Close\r\n\r\n";

				fwrite($fp, $request);
				while(!feof($fp)) {
					$data .= fread($fp,1024);
				}
				fclose($fp);
				
				$error = xml_elements('error_message',$data);
				if($error[0] != ''){
					$apiFail = true;
					$errorMsg = 'Error Msg From Eventbrite - <i>' . $error[0] . '</i>';
				} else {
					doQuery("UPDATE " . HC_TblPrefix . "eventnetwork SET IsActive = 0 WHERE NetworkID = '" . $ebID . "'");
				}
			}
		}
	}
	echo ($errorMsg != '') ? $errorMsg : '';
?>