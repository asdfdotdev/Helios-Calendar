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
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	post_only();
	
	$emapZoom = isset($_POST['emapZoom']) ? cIn($_POST['emapZoom']) : '';
	$eventfulAPI = isset($_POST['eventfulAPI']) ? cIn($_POST['eventfulAPI']) : '';
	$efUser = isset($_POST['efUser']) ? cIn($_POST['efUser']) : '';
	$efPass = isset($_POST['efPass']) ? cIn($_POST['efPass']) : '';
	$efSig = isset($_POST['efSignature']) ? cIn(strip_tags($_POST['efSignature'])) : '';
	$eventbriteAPI = isset($_POST['eventbriteKeyA']) ? cIn($_POST['eventbriteKeyA']) : '';
	$eventbriteUser = isset($_POST['eventbriteKeyU']) ? cIn($_POST['eventbriteKeyU']) : '';
	$locBrowse = isset($_POST['locBrowse']) ? cIn($_POST['locBrowse']) : '';
	$tweetHash = isset($_POST['tweetHash']) ? cIn($_POST['tweetHash']) : '';
	$showTime = isset($_POST['showtime']) ? 1 : 0;
	$googMapURL = isset($_POST['googMapURL']) ? cIn($_POST['googMapURL']) : '';
	$bitlyUser = isset($_POST['bitlyUser']) ? cIn($_POST['bitlyUser']) : '';
     $bitlyAPI = isset($_POST['bitlyAPI']) ? cIn($_POST['bitlyAPI']) : '';
	$eventbriteOrgN = (isset($_POST['ebOrgName'])) ? cIn($_POST['ebOrgName']) : '';
	$eventbriteOrgD = (isset($_POST['ebOrgDesc'])) ? cIn($_POST['ebOrgDesc']) : '';
	$eventbriteOrgID = (isset($_POST['ebOrgID'])) ? cIn($_POST['ebOrgID']) : '';
	$useComments = isset($_POST['useComments']) ? cIn($_POST['useComments']) : '';
	$disqusName = isset($_POST['disqusName']) ? cIn($_POST['disqusName']) : '';
	
	$quickLinks = "0";
	if(isset($_POST['quickLinkID']) && is_array($_POST['quickLinkID'])){
		sort($_POST['quickLinkID']);
		$quickLinks = implode(',',array_filter($_POST['quickLinkID'],'is_numeric'));
	}
	
	if($emapZoom != ''){
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventbriteAPI . "' WHERE PkID = 5");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventbriteUser . "' WHERE PkID = 6");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $disqusName . "' WHERE PkID = 25");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $emapZoom . "' WHERE PkID = 27");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventfulAPI . "' WHERE PkID = 36");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $efUser . "' WHERE PkID = 37");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $efSig . "' WHERE PkID = 39");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $locBrowse . "' WHERE PkID = 45");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $quickLinks . "' WHERE PkID = 50");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $googMapURL . "' WHERE PkID = 52");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $useComments . "' WHERE PkID = 56");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $bitlyUser . "' WHERE PkID = 57");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $bitlyAPI . "' WHERE PkID = 58");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $tweetHash . "' WHERE PkID = 59");

		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = '60'");
		$chkOrgN = (hasRows($result)) ? mysql_result($result,0,0) : '';
		if($eventbriteOrgN != '' && $chkOrgN != $eventbriteOrgN){
			$ebID = $eventbriteOrgID;
			require_once(HCPATH.HCINC.'/api/eventbrite/OrganizerEdit.php');

			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventbriteOrgN . "' WHERE PkID = 60");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventbriteOrgD . "' WHERE PkID = 61");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $ebID . "' WHERE PkID = 62");
		}
		if($locBrowse == 1){
			$lmapZoom = $_POST['lmapZoom'];
			$lmapLat = $_POST['lmapLat'];
			$lmapLon = $_POST['lmapLon'];
			$mapSyndication = $_POST['mapSyndication'];
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $lmapZoom . "' WHERE PkID = 41");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $lmapLat . "' WHERE PkID = 42");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $lmapLon . "' WHERE PkID = 43");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $mapSyndication . "' WHERE PkID = 69");
		}
		if($efPass != '')
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . base64_encode($efPass) . "' WHERE PkID = 38");
		if(isset($_POST['twtrRevoke']))
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = NULL WHERE PkID IN (46,47,63,64)");
		if(isset($_POST['twitterpin']) && $_POST['twitterpin'] != ''){
			$authToken = $authSecret = $authUserID = $authUser = '';
			$twtrPin = cIn($_POST['twitterpin']);
			include(HCPATH.HCINC.'/api/twitter/AuthorizeRequest.php');

			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $authUser . "' WHERE PkID = 63");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $authUserID . "' WHERE PkID = 64");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $authToken . "' WHERE PkID = 46");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $authSecret . "' WHERE PkID = 47");
		}

		clearCache();
		
		header("Location: " . AdminRoot . "/index.php?com=apiset&msg=1");
	} else {
		header("Location: " . AdminRoot . "/index.php?com=apiset");
	}
?>