<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	$maxDisplay = $_POST['maxRSS'];
	$allowsubmit = $_POST['allowsubmit'];
	$defaultApprove = $_POST['defaultApprove'];
	$defaultDecline = $_POST['defaultDecline'];
	$driving = $_POST['driving'];
	$weather = $_POST['weather'];
	$mostPopular = $_POST['mostPopular'];
	$browsePast = $_POST['browsePast'];
	$state = $_POST['locState'];
	$maxShow = $_POST['display'];
	$fillMax = $_POST['fill'];
	$calStartDay = $_POST['calStartDay'];
	$popDateFormat = $_POST['popDateFormat'];
	$dateFormat = $_POST['dateFormat'];
	$timeFormat = $_POST['timeFormat'];
	$WYSIWYG = $_POST['WYSIWYG'];
	$timeInput = $_POST['timeInput'];
	$series = $_POST['series'];
	$browseType = $_POST['browseType'];
	$offsetTimezone = $_POST['offsetTimezone'];
	$stats = $_POST['stats'];
	$langType = $_POST['langType'];
	$showTime = isset($_POST['showtime']) ? 1 : 0;
	$useComments = $_POST['useComments'];
	$maxNew = $_POST['maxNew'];
	$capType = $_POST['capType'];
	$capIDs = '0';
	$reCapPub = $reCapPriv = '';
	$mobiRedirect = $_POST['mobiRedirect'];
	$locSelect = $_POST['locSelect'];
	
	if($capType > 0){
		if(isset($_POST['capID'])){
			$capID = $_POST['capID'];
			foreach ($capID as $val){
				$capIDs .= "," . $val;
			}//end foreach
		}//end if
		if($capType == 2){
			$reCapPub = $_POST['reCapPub'];
			$reCapPriv = $_POST['reCapPriv'];
		}//end if
	}//end if

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
	}//end switch
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($allowsubmit) . "' WHERE PkID = 1");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($maxDisplay) . "' WHERE PkID = 2");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($defaultApprove) . "' WHERE PkID = 3");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($defaultDecline) . "' WHERE PkID = 4");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($driving) . "' WHERE PkID = 8");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($weather) . "' WHERE PkID = 9");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($mostPopular) . "' WHERE PkID = 10");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($browsePast) . "' WHERE PkID = 11");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($maxShow) . "' WHERE PkID = 12;");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($fillMax) . "' WHERE PkID = 13;");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($dateFormat) . "' WHERE PkID = 14;");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($showTime) . "' WHERE PkID = 15;");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($state) . "' WHERE PkID = 21");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($calStartDay) . "' WHERE PkID = 22");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($timeFormat) . "' WHERE PkID = 23");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($popDateFormat) . "' WHERE PkID = 24");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($langType) . "' WHERE PkID = 28");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($WYSIWYG) . "' WHERE PkID = 30");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($timeInput) . "' WHERE PkID = 31");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($capIDs) . "' WHERE PkID = 32");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($series) . "' WHERE PkID = 33");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($browseType) . "' WHERE PkID = 34");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($offsetTimezone) . "' WHERE PkID = 35");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($stats) . "' WHERE PkID = 44");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($mobiRedirect) . "' WHERE PkID = 48");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($dateValid) . "' WHERE PkID = 51");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($useComments) . "' WHERE PkID = 56");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($capType) . "' WHERE PkID = 65");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($maxNew) . "' WHERE PkID = 66");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($reCapPub) . "' WHERE PkID = 67");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($reCapPriv) . "' WHERE PkID = 68");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($locSelect) . "' WHERE PkID = 70");

	if($useComments == 1){
		$floodTime = $_POST['floodTime'];
		$reclimit = $_POST['reclimit'];
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($floodTime) . "' WHERE PkID = 25");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($reclimit) . "' WHERE PkID = 53");
	}//end if

	if($allowsubmit == 1){
		$pubCat = $_POST['pubCat'];
		$subLimit = $_POST['subLimit'];
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($pubCat) . "' WHERE PkID = 29");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($subLimit) . "' WHERE PkID = 40");
	}//end if
	
	clearCache();
		
	header("Location: " . CalAdminRoot . "/index.php?com=generalset&msg=1");	?>