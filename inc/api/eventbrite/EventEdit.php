<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	$errorMsg = '';
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(5,6);");
	if(!hasRows($result)){
		$apiFail = true;
		$errorMsg = 'Eventbrite API Settings Unavailable.';
	} else {
		$ebAPI = cOut(mysql_result($result,0,0));
		$ebUser = cOut(mysql_result($result,1,0));
		
		if($ebAPI == '' || $ebUser == ''){
			$apiFail = true;
			$errorMsg = 'Eventbrite API Settings Missing.';
		} else {
			$ebID = (!isset($ebID)) ? 0 : $ebID;
			$ebSend = ($ebID == 0) ? "/xml/event_new?app_key=".$ebAPI."&user_key=".$ebUser : "/xml/event_update?app_key=".$ebAPI."&user_key=".$ebUser;

			$endTime = (isset($_POST['ignoreendtime'])) ? $startTime : $endTime;
			$endDate = $eventDate;
			if($startTime > $endTime){
				$dateParts = explode("-", $eventDate);
				$endDate = date("Y-m-d", mktime(0,0,0,$dateParts[1],($dateParts[2]+1),$dateParts[0]));
			}
			
			$resultLoc = doQuery("SELECT NetworkID FROM " . HC_TblPrefix . "locationnetwork WHERE NetworkType = 2 AND LocationID = '" . $locID . "'");
			$venueID = (hasRows($resultLoc)) ? mysql_result($resultLoc,0,0) : '';
			$ebStatus = isset($_POST['ebStatus']) ? cIn($_POST['ebStatus']) : 'draft';
			$ebPrivacy = isset($_POST['ebPrivacy']) ? cIn($_POST['ebPrivacy']) : '0';
			$ebTimezone = isset($_POST['ebTimezone']) ? cIn($_POST['ebTimezone']) : substr(HCTZ,0,3);
			$ebCurrency = isset($_POST['ebCurrency']) ? cIn($_POST['ebCurrency']) : 'USD';
			$ebOrganizer = isset($_POST['ebOrgID']) ? cIn($_POST['ebOrgID']) : $hc_cfg[62];
			
			$eventD = utf8_encode(htmlentities(strip_tags($eventDesc)));
			if(strlen($eventD) > 400)
				$eventD = clean_truncate($eventDesc, 400) . '<br /><br /><a href="' . CalRoot . '/index.php?eID=' . $eID . '">' . utf8_encode(htmlentities($hc_lang_event['EventbriteFull'])) . '</a>';
			
			$ebSend .= "&title=" . urlencode(utf8_encode(htmlentities($eventTitle)));
			$ebSend .= "&description=" . urlencode(utf8_encode(nl2br($eventD)));
			$ebSend .= "&start_date=" . $eventDate . "+" . str_replace("'", "", $startTime);
			$ebSend .= "&end_date=" . $endDate . "+" . str_replace("'", "", $endTime);
			$ebSend .= "&timezone=" . $ebTimezone;
			$ebSend .= "&privacy=" . $ebPrivacy;
			$ebSend .= "&venue_id=" . $venueID;
			$ebSend .= "&organizer_id=" . $ebOrganizer;
			$ebSend .= "&currency=" . $ebCurrency;
			$ebSend .= "&status=" . $ebStatus;
			
			if($ebID > 0)
				$ebSend .= "&event_id=" . $ebID;
			
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
					if(xml_tag_value('status', $data) == "OK"){
						$ebID = xml_tag_value('id', $data);
					}
				}
			}
		}
	}
	echo ($errorMsg != '') ? $errorMsg : '';
?>