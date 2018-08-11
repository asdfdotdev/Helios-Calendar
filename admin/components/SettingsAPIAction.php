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

	$googleAPI = $_POST['googleAPI'];
	$emapZoom = $_POST['emapZoom'];
	$eventfulAPI = $_POST['eventfulAPI'];
	$efUser = $_POST['efUser'];
	$efPass = $_POST['efPass'];
	$efSig = $_POST['efSignature'];
	$eventbriteAPI = $_POST['eventbriteKeyA'];
	$eventbriteUser = $_POST['eventbriteKeyU'];
	$locBrowse = $_POST['locBrowse'];
	$twEmail = $_POST['twEmail'];
	$twPass = $_POST['twPass'];
	$tweetHash = $_POST['tweetHash'];
	$showTime = isset($_POST['showtime']) ? 1 : 0;
	$googMapURL = $_POST['googMapURL'];
	$bitlyUser = $_POST['bitlyUser'];
     $bitlyAPI = $_POST['bitlyAPI'];
	$eventbriteOrgN = (isset($_POST['ebOrgName'])) ? $_POST['ebOrgName'] : '';
	$eventbriteOrgD = (isset($_POST['ebOrgDesc'])) ? $_POST['ebOrgDesc'] : '';
	$eventbriteOrgID = (isset($_POST['ebOrgID'])) ? $_POST['ebOrgID'] : '';

	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($eventbriteAPI) . "' WHERE PkID = 5");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($eventbriteUser) . "' WHERE PkID = 6");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($googleAPI) . "' WHERE PkID = 26");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($emapZoom) . "' WHERE PkID = 27");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($eventfulAPI) . "' WHERE PkID = 36");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($efUser) . "' WHERE PkID = 37");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($locBrowse) . "' WHERE PkID = 45");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($twEmail) . "' WHERE PkID = 46");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($googMapURL) . "' WHERE PkID = 52");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($bitlyUser) . "' WHERE PkID = 57");
     doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($bitlyAPI) . "' WHERE PkID = 58");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($tweetHash) . "' WHERE PkID = 59");

	if($eventbriteOrgN != ''){
		$ebID = $eventbriteOrgID;
		require_once('../../events/includes/api/eventbrite/OrganizerEdit.php');
		
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($eventbriteOrgN) . "' WHERE PkID = 60");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($eventbriteOrgD) . "' WHERE PkID = 61");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($ebID) . "' WHERE PkID = 62");
	}//end if

     if($locBrowse == 1){
		$lmapZoom = $_POST['lmapZoom'];
		$lmapLat = $_POST['lmapLat'];
		$lmapLon = $_POST['lmapLon'];
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($lmapZoom) . "' WHERE PkID = 41");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($lmapLat) . "' WHERE PkID = 42");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($lmapLon) . "' WHERE PkID = 43");
	}//end if
	if($efPass != ''){
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($efPass) . "' WHERE PkID = 38");
	}//end if
	if($twPass != ''){
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($twPass) . "' WHERE PkID = 47");
	}//end if
	if($twEmail != ''){
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '1' WHERE PkID = 50");
	} else {
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = Null WHERE PkID = 47");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '0' WHERE PkID = 50");
	}//end if
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn(htmlspecialchars($efSig)) . "' WHERE PkID = 39");
	
	if(file_exists(realpath('../../events/cache/config.php'))){
		unlink('../../events/cache/config.php');
	}//end if
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../../events/cache/lmap' . $curCache . '.php'))){
		unlink('../../events/cache/lmap' . $curCache . '.php');
	}//end if

	header("Location: " . CalAdminRoot . "/index.php?com=apiset&msg=1");	?>