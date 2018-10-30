<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	post_only();
	
	$token = (isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : '';
	if(!check_form_token($token))
		go_home();
	
	$msgID = 1;
	$reCapPub = $reCapPriv = '';
	$allowsubmit = isset($_POST['allowsubmit']) ? cIn($_POST['allowsubmit']) : '';
	$defaultApprove = isset($_POST['defaultApprove']) ? cIn($_POST['defaultApprove']) : '';
	$defaultDecline = isset($_POST['defaultDecline']) ? cIn($_POST['defaultDecline']) : '';
	$driving = isset($_POST['driving']) ? cIn($_POST['driving']) : '';
	$weather = isset($_POST['weather']) ? cIn($_POST['weather']) : '';
	$mostPopular = isset($_POST['mostPopular']) ? cIn($_POST['mostPopular']) : '';
	$browsePast = isset($_POST['browsePast']) ? cIn($_POST['browsePast']) : '';
	$state = isset($_POST['locState']) ? cIn($_POST['locState']) : '';
	$maxShow = isset($_POST['display']) ? cIn($_POST['display']) : '';
	$fillMax = isset($_POST['fill']) ? cIn($_POST['fill']) : '';
	$calStartDay = isset($_POST['calStartDay']) ? cIn($_POST['calStartDay']) : '';
	$popDateFormat = isset($_POST['popDateFormat']) ? cIn($_POST['popDateFormat']) : '';
	$dateFormat = isset($_POST['dateFormat']) ? cIn($_POST['dateFormat']) : '';
	$timeFormat = isset($_POST['timeFormat']) ? cIn($_POST['timeFormat']) : '';
	$WYSIWYG = isset($_POST['WYSIWYG']) ? cIn($_POST['WYSIWYG']) : '';
	$timeInput = isset($_POST['timeInput']) ? cIn($_POST['timeInput']) : '';
	$series = isset($_POST['series']) ? cIn($_POST['series']) : '';
	$browseType = isset($_POST['browseType']) ? cIn($_POST['browseType']) : '';
	$offsetTimezone = isset($_POST['offsetTimezone']) ? cIn($_POST['offsetTimezone']) : '';
	$stats = isset($_POST['stats']) ? cIn($_POST['stats']) : '';
	$langType = isset($_POST['langType']) ? cIn($_POST['langType']) : '';
	$showTime = isset($_POST['showtime']) ? 1 : 0;
	$maxNew = isset($_POST['maxNew']) ? cIn($_POST['maxNew']) : '';
	$capType = isset($_POST['capType']) ? cIn($_POST['capType']) : '';
	$capIDs = '0';
	$locSelect = isset($_POST['locSelect']) ? cIn($_POST['locSelect']) : '';
	$mailMethod = isset($_POST['mailMethod']) ? cIn($_POST['mailMethod']) : '';
	$mailHost = isset($_POST['mailHost']) ? cIn($_POST['mailHost']) : '';
	$mailPort = isset($_POST['mailPort']) ? cIn($_POST['mailPort']) : '';
	$mailSecure = isset($_POST['mailSecure']) ? cIn($_POST['mailSecure']) : '';
	$mailAuth = isset($_POST['mailAuth']) ? cIn($_POST['mailAuth']) : '';
	$mailUser = ($mailAuth == 1) ? cIn($_POST['mailUser']) : '';
	$mailPassChg = ($mailAuth == 1) ? cIn($_POST['mailPassChg']) : '';
	$mailPassCon = ($mailAuth == 1) ? cIn($_POST['mailPassCon']) : '';
	$mailAddress = isset($_POST['mailAddress']) ? cIn($_POST['mailAddress']) : '';
	$mailName = isset($_POST['mailName']) ? cIn($_POST['mailName']) : '';
	$loginTries = isset($_POST['loginTries']) ? cIn($_POST['loginTries']) : '';
	$pubNews = isset($_POST['pubNews']) ? cIn($_POST['pubNews']) : '';
	$batchSize = isset($_POST['batchSize']) ? cIn($_POST['batchSize']) : '';
	$batchDelay = isset($_POST['batchDelay']) ? cIn($_POST['batchDelay']) : '';
	$searchWindow = isset($_POST['searchWindow']) ? cIn($_POST['searchWindow']) : '';
	$reCapStyle = isset($_POST['reCapStyle']) ? cIn($_POST['reCapStyle']) : '';
	$passStr = isset($_POST['passStr']) ? cIn($_POST['passStr']) : '';
	$passAge = isset($_POST['passAge']) ? cIn($_POST['passAge']) : '';
	$mc_select = isset($_POST['mc_select']) ? cIn($_POST['mc_select']) : '';
	$mc_dow = isset($_POST['mc_dow']) ? cIn($_POST['mc_dow']) : '';
	$float = isset($_POST['browseFloat']) ? cIn($_POST['browseFloat']) : '';
	$pubCat = isset($_POST['pubCat']) ? cIn(strip_tags($_POST['pubCat'])) : '';
	$subLimit = isset($_POST['subLimit']) ? cIn(strip_tags($_POST['subLimit'])) : '0';
	$rssStatus = (isset($_POST['rssStatus']) && is_numeric($_POST['rssStatus'])) ? cIn($_POST['rssStatus']) : '0';
	$maxDisplay = (isset($_POST['maxRSS']) && is_numeric($_POST['maxRSS'])) ? cIn($_POST['maxRSS']) : '20';
	$rssTrunc = (isset($_POST['rssTrunc']) && is_numeric($_POST['rssTrunc'])) ? cIn($_POST['rssTrunc']) : '0';
	$iCalStatus = (isset($_POST['iCalStatus']) && is_numeric($_POST['iCalStatus'])) ? cIn($_POST['iCalStatus']) : '0';
	$iCalTrunc = (isset($_POST['iCalTrunc']) && is_numeric($_POST['iCalTrunc'])) ? cIn($_POST['iCalTrunc']) : '0';
	$iCalMax = (isset($_POST['iCalMax']) && is_numeric($_POST['iCalMax'])) ? cIn($_POST['iCalMax']) : '20';
	$iCalRef = (isset($_POST['iCalRef']) && is_numeric($_POST['iCalRef'])) ? cIn($_POST['iCalRef']) : '360';
	$jssMax = (isset($_POST['jssMax']) && is_numeric($_POST['jssMax'])) ? cIn($_POST['jssMax']) : '50';
	$sendPast = (isset($_POST['sendPast']) && is_numeric($_POST['sendPast'])) ? cIn($_POST['sendPast']) : '0';
	
	if($capType > 0){
		if(isset($_POST['capID']))
			$capIDs .= ','.implode(',',array_filter($_POST['capID'],'is_numeric'));
		if($capType == 2){
			$reCapPub = cIn(strip_tags($_POST['reCapPub']));
			$reCapPriv = cIn(strip_tags($_POST['reCapPriv']));
		}
	}
	
	switch($popDateFormat){
		case '%m/%d/%Y':
			$dateValid = 'MM/dd/yyyy';
			break;
		case '%d/%m/%Y':
			$dateValid = 'dd/MM/yyyy';
			break;
		case '%Y/%m/%d':
			$dateValid = 'yyyy/MM/dd';
			break;
	}
	
	if($mailPassChg != '' && $mailPassChg == $mailPassCon && $mailAuth == 1){
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array(base64_encode($mailPassChg), 76));
	} elseif($mailPassChg != $mailPassCon && $mailAuth == 1){
		$msgID = 2;
	} else if($mailAuth == 0){
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '' WHERE PkID = 76");
	}
	
	if($allowsubmit != ''){
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($allowsubmit, 1));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($defaultApprove, 3));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($defaultDecline, 4));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($driving, 8));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($weather, 9));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mostPopular, 10));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($browsePast, 11));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($maxShow, 12));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($fillMax, 13));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($dateFormat, 14));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($showTime, 15));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($state, 21));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($calStartDay, 22));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($timeFormat, 23));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($popDateFormat, 24));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($passAge, 26));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($langType, 28));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($WYSIWYG, 30));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($timeInput, 31));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($capIDs, 32));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($series, 33));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($browseType, 34));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($offsetTimezone, 35));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($stats, 44));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($float, 48));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($dateValid, 51));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array(($searchWindow - 1), 53));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($pubNews, 54));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($jssMax, 60));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($capType, 65));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($maxNew, 66));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($reCapPub, 67));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($reCapPriv, 68));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($locSelect, 70));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mailMethod, 71));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mailHost, 72));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mailPort, 73));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mailSecure, 74));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mailUser, 75));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mailAuth, 77));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mailAddress, 78));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mailName, 79));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($loginTries, 80));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($batchSize, 81));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($batchDelay, 82));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($reCapStyle, 90));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($passStr, 91));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mc_select, 92));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($mc_dow, 93));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($rssStatus, 106));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($iCalStatus, 108));
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($sendPast, 126));

		if($allowsubmit == 1){
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array(cIn($pubCat), 29));
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array(cIn($subLimit), 40));
		}
		if($rssStatus == 1){
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($maxDisplay, 2));
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($rssTrunc, 107));
		}
		if($iCalStatus == 1){
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($iCalMax, 88));
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($iCalRef, 89));
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($iCalTrunc, 109));
		}
		clearCache();

		header("Location: " . AdminRoot . "/index.php?com=generalset&msg=" . $msgID);
	} else {
		header("Location: " . AdminRoot . "/index.php?com=generalset");
	}
?>