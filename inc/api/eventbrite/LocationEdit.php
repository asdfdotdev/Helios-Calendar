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
			$ebID = (!isset($ebID)) ? 0 : $ebID;
			$ebSend = ($ebID == 0) ? "/xml/venue_new?app_key=".$ebAPI."&user_key=".$ebUser : "/xml/venue_update?app_key=".$ebAPI."&user_key=".$ebUser;

			$ebOrganizer = isset($_POST['ebOrgID']) ? cIn($_POST['ebOrgID']) : $hc_cfg[62];
			$iso_country = isset($_POST['selCountry']) ? cIn($_POST['selCountry']) : '';
			
			if($ebID > 0)
				$ebSend .= "&id=" . $ebID;
			else
				$ebSend .= "&organizer_id=" . $ebOrganizer;
			$ebSend .= "&name=" . urlencode(utf8_encode(htmlentities($name)));
			$ebSend .= "&address=" . urlencode(utf8_encode(htmlentities($address)));
			$ebSend .= "&address_2=" . urlencode(utf8_encode(htmlentities($address2)));
			$ebSend .= "&city=" . urlencode(utf8_encode(htmlentities($city)));
			$ebSend .= "&region=" . $state;
			$ebSend .= "&postal_code=" . urlencode(utf8_encode(htmlentities($zip)));
			$ebSend .= "&country_code=" . $iso_country;
			
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