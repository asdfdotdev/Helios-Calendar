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
			$efSend = ($efID == '') ? "/rest/venues/new" : "/rest/venues/modify";

			$ip = gethostbyname("api.evdb.com");
			if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
				$apiFail = true;
				$errorMsg = 'Connection to Eventful Service Failed.';
			} else {
				$efSend .= "?app_key=" . $efKey . "&user=" . $efUser . "&password=" . $efPass;
				
				if($efID != ''){
					$efSend .= "&id=" . $efID;
				}

				$efSend .= ($lName != '') ? '&name=' . urlencode(utf8_encode(htmlentities($lName))) : '';
				$efSend .= ($address != '' && $address2 != '') ? '&address=' . urlencode(utf8_encode(htmlentities($address . ' ' . $address2))) : '&address=' . urlencode(utf8_encode(htmlentities($address)));
				$efSend .= ($city != '') ? "&city=" . urlencode(utf8_encode(htmlentities($city))) : '';
				$efSend .= ($state != '') ? '&region=' . urlencode(utf8_encode(htmlentities($state))) : '';
				$efSend .= ($zip != '') ? '&postal_code=' . urlencode(utf8_encode(htmlentities($zip))) : '';
				$efSend .= ($country != '') ? '&country=' . urlencode(utf8_encode(htmlentities($country))) : '';
				
				if($url != ''){
					$efSend .= "&url=" . $url;
					$efSend .= "&url_type=19";
				}

				$descript = utf8_encode(htmlentities(strip_tags($descript)));
				if(strlen($descript) > 400){
					$descript = substr($eventDesc,0,400) . '...<br /><br /><a href="' . CalRoot . '/index.php?com=location&lID=' . $lID . '">' . $hc_lang_event['EventfulFull'] . '</a>';
				}
				$descript .= "<p>" . utf8_encode(htmlentities($efSig)) . "<br>";
				$descript .= "<b><a href=\"" . CalRoot . "\">" . CalRoot . "/</a></b></p>";
				
				$efSend .= "&description=" . urlencode(nl2br($descript));
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
	}?>