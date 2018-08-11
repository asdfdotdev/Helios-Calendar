<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
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
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
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
			$efSend = ($efID == '') ? "/rest/events/new" : "/rest/events/modify";

			$ip = gethostbyname("api.evdb.com");
			if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
				$apiFail = true;
				$errorMsg = 'Connection to Eventful Service Failed.';
			} else {
				$efSend .= "?app_key=" . $efKey;
				$efSend .= "&user=" . $efUser;
				if($efID != '')
					$efSend .= "&id=" . $efID;
				
				$efSend .= "&password=" . urlencode($efPass);
				$efSend .= "&title=" . urlencode(utf8_encode(htmlentities($eventTitle)));

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
				
				$resultLoc = doQuery("SELECT NetworkID FROM " . HC_TblPrefix . "locationnetwork WHERE NetworkType = 1 AND LocationID = '" . cIn($locID) . "'");
				if(hasRows($resultLoc)){
					$efSend .= "&venue_id=" . mysql_result($resultLoc,0,0);
				} else {
					if($locID > 0){
						$resultLoc = doQuery("SELECT * From " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($locID) . "'");
						if(hasRows($resultLoc)){
							$locName = mysql_result($resultLoc,0,1);
							$locAddress = mysql_result($resultLoc,0,2);
							$locAddress2 = mysql_result($resultLoc,0,3);
							$locCity = mysql_result($resultLoc,0,4);
							$locState = mysql_result($resultLoc,0,5);
							$locCountry = mysql_result($resultLoc,0,6);
							$locZip = mysql_result($resultLoc,0,7);
						}
					}
					$eventD .= "<p><br><b>Venue</b><br>";
					$eventD .= ($locName != '') ? utf8_encode(htmlentities($locName)) . '<br>' : '';
					$eventD .= ($locAddress != '') ? utf8_encode(htmlentities($locAddress)) . '<br>' : '';
					$eventD .= ($locAddress2 != '') ? utf8_encode(htmlentities($locAddress2)) . '<br>' : '';
					$eventD .= ($locCity != '') ? utf8_encode(htmlentities($locCity)) . '<br>' : '';
					$eventD .= ($locState != '') ? utf8_encode(htmlentities($locState)) . '<br>' : '';
					$eventD .= ($locCountry != '') ? utf8_encode(htmlentities($locCountry)) . '<br>' : '';
					$eventD .= ($locZip != '') ? utf8_encode(htmlentities($locZip)) . '<br>' : '';
					$eventD .= "</p>";
					$efSend .= "&venue_id=";
				}
				$eventD .= "<p>" . utf8_encode(htmlentities($efSig)) . "<br>";
				$eventD .= "<b><a href=\"" . CalRoot . "\">" . CalRoot . "/</a></b></p>";
				
				$efSend .= "&description=" . urlencode(nl2br($eventD));
				$efSend .= "&privacy=1";	//1 = public, 2 = private
				
				$request = "GET " . $efSend . " HTTP/1.0\r\nHost: api.evdb.com\r\nConnection: Close\r\n\r\n";

				fwrite($fp, $request);
				$data = '';
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
			echo ($errorMsg != '') ? $errorMsg : '';
		}
	}
?>