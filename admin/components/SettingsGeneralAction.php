<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
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
	$emailNotice = $_POST['emailNotice'];
	$googleAPI = $_POST['googleAPI'];
	$yahooAPI = $_POST['yahooAPI'];
	$mapZoom = $_POST['mapZoom'];
	$pubCat = $_POST['pubCat'];
	$WYSIWYG = $_POST['WYSIWYG'];
	$timeInput = $_POST['timeInput'];
	$series = $_POST['series'];
	$browseType = $_POST['browseType'];
	$offsetTimezone = $_POST['offsetTimezone'];
	
	$capIDs = "0";
	if(isset($_POST['capID'])){
		$capID = $_POST['capID'];
		foreach ($capID as $val){
			$capIDs .= "," . $val;
		}//end foreach
	}//end if
	
	$showTime = 0;
	if(isset($_POST['showtime'])){
		$showTime = 1;
	}//end if
	
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
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($emailNotice) . "' WHERE PkID = 25");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($googleAPI) . "' WHERE PkID = 26");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($mapZoom) . "' WHERE PkID = 27");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($yahooAPI) . "' WHERE PkID = 28");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($pubCat) . "' WHERE PkID = 29");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($WYSIWYG) . "' WHERE PkID = 30");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($timeInput) . "' WHERE PkID = 31");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($capIDs) . "' WHERE PkID = 32");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($series) . "' WHERE PkID = 33");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($browseType) . "' WHERE PkID = 34");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($offsetTimezone) . "' WHERE PkID = 35");
	
	header("Location: " . CalAdminRoot . "/index.php?com=generalset&msg=1");	?>