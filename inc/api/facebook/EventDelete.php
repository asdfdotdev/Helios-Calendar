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
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(117,118,120,121,123)");
	if(!hasRows($result)){
		$apiFail = true;
		$errorMsg = 'Facebook API Settings Unavailable.';
	} else {
		$app_id = mysql_result($result,0,0);
		$app_secret = mysql_result($result,1,0);
		$fb_token = mysql_result($result,2,0);
		$user_token = mysql_result($result,3,0);
		$fb_id = mysql_result($result,4,0);
		
		if($app_id == '' || $app_secret == '' || $fb_token == '' || $fb_id == ''){
			$apiFail = true;
			$errorMsg = 'Facebook API Settings Missing.';
		} else {
			require_once(HCPATH.HCINC.'/api/facebook/sdk/facebook.php');

			$facebook = new Facebook(array(
				'appId' => $app_id,
				'secret' => $app_secret,
				'cookie' => true
			));
			$params = array('access_token' => $fb_token);
			$status = $facebook->api('/'.$netID, 'delete', $params);
			
			if($status != true){
				$apiFail = true;
				$errorMsg = 'Facebook Event Deletion Failed';
			}
		}
	}
	echo ($errorMsg != '') ? $errorMsg : '';
?>