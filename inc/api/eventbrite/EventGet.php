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
			if(!$fp = fsockopen("ssl://www.eventbrite.com", 443, $errno, $errstr, 10))
				$fp = fsockopen("www.eventbrite.com", 80, $errno, $errstr, 10);
			
			if(!$fp){
				$apiFail = true;
				$errorMsg = 'Connection to Eventbrite Service Failed.';
			} else {
				$data = '';
				$request = "GET /xml/event_get?app_key=".$ebAPI."&user_key=".$ebUser."&id=".$ebID." HTTP/1.1\r\nHost: www.eventbrite.com\r\nConnection: Close\r\n\r\n";
				
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
					$orgtags = xml_elements('organizer',$data);
					$organizerID = xml_tag_value('id', $orgtags[0]);
					$status = xml_tag_value('status', $data);
					$privacy = xml_tag_value('privacy', $data);
					$currency = xml_tag_value('currency', $data);
					
					$tickets = xml_elements('ticket',$data);
					foreach ($tickets as $ticket){
						if(xml_tag_value('id',$ticket) != ''){
							$ebtickets[] = array(
							'id'		=>	xml_tag_value('id',$ticket),
							'name'	=>	xml_tag_value('name',$ticket),
							'type'	=>	xml_tag_value('type',$ticket),
							'price'	=>	xml_tag_value('price',$ticket),
							'qty'	=>	xml_tag_value('quantity_available',$ticket),
							'end'	=>	xml_tag_value('end_date',$ticket),
							);
						}
					}
				}
			}
		}
	}
	echo ($errorMsg != '') ? $errorMsg : '';
?>