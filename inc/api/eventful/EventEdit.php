<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	$errorMsg = '';
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
	if(!hasRows($result) || mysql_result($result,0,0) == '' || mysql_result($result,1,0) == '' || mysql_result($result,2,0) == ''){
		$apiFail = true;
		$errorMsg = 'Eventful API Settings Unavailable.';
	} else {
		$efKey = mysql_result($result,0,0);
		$efUser = mysql_result($result,1,0);
		$efPass = base64_decode(mysql_result($result,2,0));
		$efSig = mysql_result($result,3,0);
		$efID = (!isset($efID)) ? 0 : $efID;
		$efSend = ($efID == '') ? "/rest/events/new" : "/rest/events/modify";

		$resultLoc = doQuery("SELECT NetworkID FROM " . HC_TblPrefix . "locationnetwork WHERE NetworkType = 1 AND LocationID = '" . cIn($locID) . "'");
		if(!hasRows($resultLoc)){
			$apiFail = true;
			$errorMsg = 'Eventful event submission requires valid Eventful Venue ID.';
		} else {
			$efSend .= "?app_key=" . $efKey;
			$efSend .= "&user=" . $efUser;
			if($efID != '')
				$efSend .= "&id=" . $efID;

			$efSend .= "&password=" . urlencode($efPass);
			$efSend .= "&title=" . urlencode(utf8_encode(htmlentities($eventTitle)));
			$efSend .= "&venue_id=" . mysql_result($resultLoc,0,0);

			if($tbd == 0){
				$efSend .= "&start_time=" . $eventDate . "T" . str_replace("'", "", $startTime);
				if(!isset($_POST['ignoreendtime'])){
					$endDate = $eventDate;
					if($startTime > $endTime){
						$dateParts = explode("-", $eventDate);
						$endDate = date("Y-m-d", mktime(0,0,0,$dateParts[1],($dateParts[2]+1),$dateParts[0]));
					}
					$efSend .= "&stop_time=" . $endDate . "T" . str_replace("'", "", $endTime);
				}
				$efSend .= "&all_day=";
			} else {
				$efSend .= "&start_time=" . $eventDate . "T00:00:00";
				$efSend .= "&all_day=1";
			}
			$efSend .= "&price=" . urlencode(utf8_encode(htmlentities($cost)));

			$tags = '';
			$catIDs = "'" . implode("','",array_filter($catID,'is_numeric')) . "'";
			$resultC = doQuery("SELECT CategoryName FROM " . HC_TblPrefix . "categories WHERE PkID IN (" . $catIDs . ")");
			if(hasRows($resultC)){
				while($row = mysql_fetch_row($resultC)){
					$tags .= str_replace(" ", "_", $row[0]) . " ";
				}
			}
			$efSend .= "&tags=" . urlencode(utf8_encode(htmlentities($tags))) . "Helios_Calendar";

			$eventDesc = utf8_encode(htmlentities(strip_tags($eventDesc)));
			if(strlen($eventDesc) > 400){
				$eventD = substr($eventDesc,0,400) . '...<br /><br /><a href="' . CalRoot . '/index.php?com=detail&eID=' . $eID . '">' . utf8_encode(htmlentities($hc_lang_event['EventfulFull'])) . '</a>';
			} else {
				$eventD = $eventDesc;
			}

			$eventD .= "<p>" . utf8_encode(htmlentities($efSig)) . "<br>";
			$eventD .= "<b><a href=\"" . CalRoot . "\">" . CalRoot . "/</a></b></p>";
			$efSend .= "&description=" . urlencode(nl2br($eventD));
			$efSend .= "&privacy=1";	//1 = public, 2 = private

			if(!$fp = fsockopen("api.evdb.com", 80, $errno, $errstr, 20)){
				$apiFail = true;
				$errorMsg = 'Connection to Eventful Service Failed.';
			} else {
				$data = '';
				$request = "GET " . $efSend . " HTTP/1.0\r\nHost: api.evdb.com\r\nConnection: Close\r\n\r\n";

				fwrite($fp, $request);

				while(!feof($fp)) {
					$data .= fread($fp,1024);
				}
				fclose($fp);

				$fetched = xml2array($data);
				if($fetched[0]['name'] == 'error'){
					$apiFail = true;
					$errorMsg = 'Error Msg From Eventful - <i>' . $fetched[0]['elements'][0]['value'] . '</i>';
				} else {
					$stopEF = count($fetched[0]['elements']);
					for($x=0;$x<$stopEF;$x++){
						if($fetched[0]['elements'][$x]['name'] == 'id'){
							$efID = $fetched[0]['elements'][$x]['value'];
							break;
						}
					}
				}
			}
		}

		echo ($errorMsg != '') ? $errorMsg : '';
	}
?>