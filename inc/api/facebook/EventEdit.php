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
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(117,118,120,123)");
	if(!hasRows($result)){
		$apiFail = true;
		$errorMsg = 'Facebook API Settings Unavailable.';
	} else {
		$app_id = mysql_result($result,0,0);
		$app_secret = mysql_result($result,1,0);
		$fb_token = mysql_result($result,2,0);
		$fb_id = mysql_result($result,3,0);
		
		if($app_id == '' || $app_secret == '' || $fb_token == '' || $fb_id == ''){
			$apiFail = true;
			$errorMsg = 'Facebook API Settings Missing.';
		} else {
			$action = ($fbID == '') ? '/'.$fb_id.'/events' : '/'.$fbID;
			$fbStart = ($startTimeHour != '') ? $eventDate.' '.$startTimeHour.":".$startTimeMins.":00" : $eventDate;
			$fbEnd = $eventDate;
			if($tbd == 0 && !isset($_POST['ignoreendtime']) && $startTime > $endTime){
				$dateParts = explode("-", $eventDate);
				$fbEnd = date("Y-m-d", mktime(0,0,0,$dateParts[1],($dateParts[2]+1),$dateParts[0]));}

			require_once(HCPATH.HCINC.'/api/facebook/sdk/facebook.php');

			$facebook = new Facebook(array(
				'appId' => $app_id,
				'secret' => $app_secret,
				'cookie' => true
			));
			$params = array(
				'access_token' => $fb_token,
				'name' => $eventTitle,
				'start_time' => strftime("%s",strtotime($fbStart)),
				'description' => html_entity_decode(strip_tags(str_replace("<p>","<p> ",$eventDesc))),
				'location' => $name . ' ' . str_replace('<br />',' ',buildAddress($add,$add2,$city,$region,$postal,$country,$hc_lang_config['AddressType'])),
				'privacy_type' => 'OPEN',
			);
			if($endTimeHour != '')
				$params['end_time'] = strftime("%s",strtotime($fbEnd . ' ' . $endTimeHour.":".$endTimeMins.":00"));
			
			$status = $facebook->api($action, 'post', $params);
			
			if(isset($status['id']) || $status === true){
				$fbID = $status['id'];
			} else {
				$apiFail = true;
				$errorMsg = 'Facebook Event Submission Failed';
			}
		}
	}
	echo ($errorMsg != '') ? $errorMsg : '';
?>