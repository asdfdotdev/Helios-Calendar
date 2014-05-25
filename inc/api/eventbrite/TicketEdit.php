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
			for($x=1;$x<=5;++$x){
				$ticketID = isset($_POST['ticketid'.$x]) ? cIn($_POST['ticketid'.$x]) : '';
				$ticket = isset($_POST['ticket'.$x]) ? cIn($_POST['ticket'.$x]) : '';
				$priceType = isset($_POST['priceType'.$x]) ? cIn($_POST['priceType'.$x]) : '';
				$qty = isset($_POST['qty'.$x]) ? cIn($_POST['qty'.$x]) : '';
				$fee = isset($_POST['fee'.$x]) ? '0' : '1';
				$end = (isset($_POST['end'.$x]) && $_POST['end'.$x] != '') ? dateToMySQL(cIn($_POST['end'.$x]), $hc_cfg[24]) : $eventDate;
				$end = ($end > $eventDate) ? $eventDate . " " . str_replace("'", "", $startTime) : $end ." 00:00:00";
				
				if($ticket == '')
					break;
				
				switch($priceType){
					case 0:
						//	fixed
						$donation = '0';
						$price = isset($_POST['price'.$x]) ? cIn($_POST['price'.$x]) : '';
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
				$ebSend = ($ticketID == '') ? 
					"/xml/ticket_new?app_key=".$ebAPI."&user_key=".$ebUser."&event_id=" . $ebID : 
					"/xml/ticket_update?app_key=".$ebAPI."&user_key=".$ebUser."&id=".$ticketID;
				$ebSend .= "&is_donation=" . $donation;
				$ebSend .= "&name=" . urlencode(utf8_encode(htmlentities($ticket)));
				$ebSend .= "&price=" . $price;
				$ebSend .= "&quantity=" . $qty;
				$ebSend .= "&start_sales=" . urlencode(date("Y-m-d H:i:s"));
				$ebSend .= "&end_sales=" . urlencode($end);
				$ebSend .= "&include_fee=" . $fee;
				$ebSend .= "&max=" . $qty;
				
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
						//	Successful
					}
				}
			}
		}
	}
	echo ($errorMsg != '') ? $errorMsg : '';
?>
