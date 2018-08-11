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
			$ebSend = ($ebID == 0) ? "/xml/event_new" : "/xml/event_update";

			$ip = gethostbyname("www.eventbrite.com");
			if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
				$apiFail = true;
				$errorMsg = 'Connection to Eventbrite Service Failed.';
			} else {
				$ebSend .= "?app_key=" . $ebAPI;
				$ebSend .= "&user_key=" . $ebUser;
				if($ebID > 0){
					$ebSend .= "&event_id=" . $ebID;
				} else {
					$ebSend .= "&currency=" . cIn($_POST['currency']);
				}
				$ebSend .= "&title=" . urlencode(utf8_encode(htmlentities($eventTitle)));
				
				$ebSend .= "&start_date=" . $eventDate . "+" . str_replace("'", "", $startTime);
				
				$endTime = (isset($_POST['ignoreendtime'])) ? $startTime : $endTime;
				
				$endDate = $eventDate;
				if($startTime > $endTime){
					$dateParts = explode("-", $eventDate);
					$endDate = date("Y-m-d", mktime(0,0,0,$dateParts[1],($dateParts[2]+1),$dateParts[0]));
				}
				$ebSend .= "&end_date=" . $endDate . "+" . str_replace("'", "", $endTime);
				
				$eventD = $eventDesc;

				$resultLoc = doQuery("SELECT NetworkID FROM " . HC_TblPrefix . "locationnetwork WHERE NetworkType = 2 AND LocationID = '" . $locID . "'");
				if(hasRows($resultLoc)){
					$ebSend .= "&venue_id=" . mysql_result($resultLoc,0,0);
					$ebSend .= "&organizer_id=" . $hc_cfg[62];
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
					$eventD .= "<p><b>Venue</b><br>";
					$eventD .= ($locName != '') ? $locName . '<br>' : '';
					$eventD .= ($locAddress != '') ? $locAddress . '<br>' : '';
					$eventD .= ($locAddress2 != '') ? $locAddress2 . '<br>' : '';
					$eventD .= ($locCity != '') ? $locCity . ', ' : '';
					$eventD .= ($locState != '') ? $locState . ' ' : '';
					$eventD .= ($locZip != '') ? $locZip . '<br>' : '';
					$eventD .= ($locCountry != '') ? $locCountry . '<br>' : '';
					$eventD .= "</p>";
					$ebSend .= "&venue_id=";
				}
				$eventD .= "<b><a href=\"" . CalRoot . "\">" . CalRoot . "/</a></b></p>";
				
				$ebSend .= "&description=" . urlencode(utf8_encode(nl2br($eventD)));
				$ebSend .= "&status=live";	// options: draft or live
				$ebSend .= "&privacy=1";		// 1 = public, 0 = private
				
				$request = "GET " . $ebSend . " HTTP/1.0\r\nHost: www.eventbrite.com\r\nConnection: Close\r\n\r\n";
				fwrite($fp, $request);
				$data = '';
				while(!feof($fp)) {
					$data .= fread($fp,1024);
				}
				fclose($fp);

				//	Uncomment the following line for debugging, it will display your request sent to Eventbrite.
				//	echo $ebSend;

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
						}
					}
					if($ebID != '' && $ebNew == true){
						for($x=1;$x<=5;$x++){
							$ebSend = "/xml/ticket_new?app_key=" . $ebAPI . "&user_key=" . $ebUser . "&event_id=" . $ebID;
							if($_POST['ticket' . $x] !=  ''){
								if(($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
									switch(cIn($_POST['priceType' . $x])){
										case 0:
											//	fixed
											$donation = '0';
											$price = cIn($_POST['price' . $x]);
											break;
										case 1:
											//	free
											$donation = '0';
											$price = '0.00';
											break;
										case 2:
											//	donation
											$donation = '1';
											$price = '';
											break;
									}

									$ebSend .= "&start_sales=" . date("Y-m-d+H:i:s");
									$ebSend .= "&end_sales=" . $eventDate . "+" . str_replace("'", "", $startTime);
									$ebSend .= "&is_donation=" . $donation;
									$ebSend .= "&name=" . urlencode(utf8_encode(htmlentities(cIn($_POST['ticket' . $x]))));
									$ebSend .= "&price=" . $price;
									$ebSend .= "&quantity=" . cIn($_POST['qty' . $x]);
									$ebSend .= (isset($_POST['fee' . $x])) ? "&include_fee=0" : "&include_fee=1";

									$request = "GET " . $ebSend . " HTTP/1.0\r\nHost: www.eventbrite.com\r\nConnection: Close\r\n\r\n";
									
									fwrite($fp, $request);
									$data = '';
									while(!feof($fp)) {
										$data .= fread($fp,1024);
									}
									fclose($fp);

									$fetched = xml2array($data);
									if($fetched[0]['name'] == 'error'){
										$apiFail = true;
										$errorMsg = 'Error Msg From Eventbrite - <i>' . $fetched[0]['elements'][0]['value'] . '</i>';
										break;
									}
								}
							}
						}
					}
				}
			}
			echo ($errorMsg != '') ? $errorMsg : '';
		}
	}
?>