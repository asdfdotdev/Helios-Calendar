<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
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
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn(htmlspecialchars($efSig)) . "' WHERE PkID = 39");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($locBrowse) . "' WHERE PkID = 45");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($googMapURL) . "' WHERE PkID = 52");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($bitlyUser) . "' WHERE PkID = 57");
     doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($bitlyAPI) . "' WHERE PkID = 58");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($tweetHash) . "' WHERE PkID = 59");

	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = '60'");
	$chkOrgN = (hasRows($result)) ? mysql_result($result,0,0) : '';
	if($eventbriteOrgN != '' && $chkOrgN != $eventbriteOrgN){
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
		$mapSyndication = $_POST['mapSyndication'];
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($lmapZoom) . "' WHERE PkID = 41");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($lmapLat) . "' WHERE PkID = 42");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($lmapLon) . "' WHERE PkID = 43");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($mapSyndication) . "' WHERE PkID = 69");
	}//end if
	if($efPass != ''){
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . base64_encode(cIn($efPass)) . "' WHERE PkID = 38");
	}//end if

	if(isset($_POST['twtrRevoke'])){
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = NULL WHERE PkID IN (46,47,63,64)");
	}//end if

	if(isset($_POST['twitterpin']) && $_POST['twitterpin'] != ''){
		$authToken = $authSecret = $authUserID = $authUser = '';
		$twtrPin = cIn($_POST['twitterpin']);
		include('../../events/includes/api/twitter/AuthorizeRequest.php');

		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($authUser) . "' WHERE PkID = 63");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($authUserID) . "' WHERE PkID = 64");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($authToken) . "' WHERE PkID = 46");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($authSecret) . "' WHERE PkID = 47");
	}//end if
	
	clearCache();

	header("Location: " . CalAdminRoot . "/index.php?com=apiset&msg=1");	?>