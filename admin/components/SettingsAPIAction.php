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
	
	$twtrRevoke = $fbRevoke = false;
	$emapZoom = isset($_POST['emapZoom']) ? cIn($_POST['emapZoom']) : '';
	$eventbriteAPI = isset($_POST['eventbriteKeyA']) ? cIn($_POST['eventbriteKeyA']) : '';
	$eventbriteUser = isset($_POST['eventbriteKeyU']) ? cIn($_POST['eventbriteKeyU']) : '';
	$locBrowse = isset($_POST['locBrowse']) ? cIn($_POST['locBrowse']) : '';
	$tweetHash = isset($_POST['tweetHash']) ? cIn($_POST['tweetHash']) : '';
	$showTime = isset($_POST['showtime']) ? 1 : 0;
	$bitlyUser = isset($_POST['bitlyUser']) ? cIn($_POST['bitlyUser']) : '';
     $bitlyAPI = isset($_POST['bitlyAPI']) ? cIn($_POST['bitlyAPI']) : '';
	$eventbriteOrgID = (isset($_POST['ebOrgID'])) ? cIn($_POST['ebOrgID']) : '';
	$eventbritePaypal = (isset($_POST['eventbritePaypal'])) ? cIn($_POST['eventbritePaypal']) : '';
	$eventbriteGoogleID = (isset($_POST['eventbriteGoogleID'])) ? cIn($_POST['eventbriteGoogleID']) : '';
	$eventbriteGoogleKey = (isset($_POST['eventbriteGoogleKey'])) ? cIn($_POST['eventbriteGoogleKey']) : '';
	$useComments = (isset($_POST['useComments']) && is_numeric($_POST['useComments'])) ? cIn($_POST['useComments']) : 0;
	$mapLibrary = (isset($_POST['mapLibrary']) && is_numeric($_POST['mapLibrary'])) ? cIn($_POST['mapLibrary']) : '';
	$twtrComKey = (isset($_POST['twtrComKey'])) ? cIn($_POST['twtrComKey']) : '';
	$twtrComSec = (isset($_POST['twtrComSec'])) ? cIn($_POST['twtrComSec']) : '';
	$twtrSignIn = (isset($_POST['twtrSignIn']) && is_numeric($_POST['twtrSignIn'])) ? cIn($_POST['twtrSignIn']) : 0;
	$fbAppID = (isset($_POST['fbAppID'])) ? cIn($_POST['fbAppID']) : '';
	$fbAppSec = (isset($_POST['fbAppSec'])) ? cIn($_POST['fbAppSec']) : '';
	$fbSignIn = (isset($_POST['fbSignIn']) && is_numeric($_POST['fbSignIn'])) ? cIn($_POST['fbSignIn']) : 0;
	$fbConOpts = (isset($_POST['fbConOpts'])) ? explode('||',cIn($_POST['fbConOpts'])) : '';
	$googID = (isset($_POST['googID'])) ? cIn($_POST['googID']) : '';
	$googSec = (isset($_POST['googSec'])) ? cIn($_POST['googSec']) : '';
	$googSignIn = (isset($_POST['googSignIn']) && is_numeric($_POST['googSignIn'])) ? cIn($_POST['googSignIn']) : 0;
	$quickLinks = "0";
	$fbActiveI = (isset($fbConOpts[0])) ? cIn($fbConOpts[0]) : '';
	$fbActiveT = (isset($fbConOpts[1])) ? cIn($fbConOpts[1]) : '';
	
	if($twtrComKey == '' || $twtrComSec == ''){
		$twtrSignIn = 0;
		$twtrRevoke = true;
	}
	if($fbAppID == '' || $fbAppSec == ''){
		$fbSignIn = 0;
		$fbRevoke = true;
	}
	if($googID == '' || $googSec == ''){
		$googSignIn = 0;
	}
	if(isset($_POST['quickLinkID']) && is_array($_POST['quickLinkID'])){
		sort($_POST['quickLinkID']);
		$quickLinks = implode(',',array_filter($_POST['quickLinkID'],'is_numeric'));
	}
	if($emapZoom != ''){
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventbriteAPI . "' WHERE PkID = 5");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventbriteUser . "' WHERE PkID = 6");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $emapZoom . "' WHERE PkID = 27");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $locBrowse . "' WHERE PkID = 45");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $quickLinks . "' WHERE PkID = 50");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $mapLibrary . "' WHERE PkID = 55");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $useComments . "' WHERE PkID = 56");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $bitlyUser . "' WHERE PkID = 57");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $bitlyAPI . "' WHERE PkID = 58");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $tweetHash . "' WHERE PkID = 59");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $twtrComKey . "' WHERE PkID = 111");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $twtrComSec . "' WHERE PkID = 112");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $twtrSignIn . "' WHERE PkID = 113");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $fbSignIn . "' WHERE PkID = 114");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $googSignIn . "' WHERE PkID = 115");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $fbAppID . "' WHERE PkID = 117");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $fbAppSec . "' WHERE PkID = 118");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $fbActiveT . "' WHERE PkID = 120");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $fbActiveI . "' WHERE PkID = 123");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $googID . "' WHERE PkID = 124");
		doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $googSec . "' WHERE PkID = 125");
		
		if($eventbriteAPI != '' && $eventbriteUser != ''){
			if($eventbriteOrgID != '')
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventbriteOrgID . "' WHERE PkID = 62");
			
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventbritePaypal . "' WHERE PkID = 103");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventbriteGoogleID . "' WHERE PkID = 104");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $eventbriteGoogleKey . "' WHERE PkID = 105");
		}
		if($mapLibrary == 1){
			$googMapURL = isset($_POST['googMapURL']) ? cIn($_POST['googMapURL']) : '';
			$googMapVer = isset($_POST['googMapVer']) ? cIn($_POST['googMapVer']) : '';

			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $googMapURL . "' WHERE PkID = 52");
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $googMapVer . "' WHERE PkID = 61");
			
		} else if($mapLibrary == 2){
			$mapLayer = (isset($_POST['mapLayer']) && is_numeric($_POST['mapLayer'])) ? cIn($_POST['mapLayer']) : '';

			switch($mapLayer){
				case 2:
					$bingAPI = isset($_POST['bingAPI']) ? cIn($_POST['bingAPI']) : '';
					doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $bingAPI . "' WHERE PkID = 96");
					break;
				case 3:
					$yahooAPI = isset($_POST['yahooAPI']) ? cIn($_POST['yahooAPI']) : '';
					doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $yahooAPI . "' WHERE PkID = 95");
					break;
			}
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $mapLayer . "' WHERE PkID = 94");
		}
		switch($useComments){
			case 1:
				$disqusName = isset($_POST['disqusName']) ? cIn($_POST['disqusName']) : '';
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $disqusName . "' WHERE PkID = 25");
				break;
			case 2:
				$fbcPosts = (isset($_POST['fbcPosts']) && is_numeric($_POST['fbcPosts'])) ? cIn($_POST['fbcPosts']) : '';
				$fbcWidth = (isset($_POST['fbcWidth']) && is_numeric($_POST['fbcWidth'])) ? cIn($_POST['fbcWidth']) : '';
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $fbcPosts . "' WHERE PkID = 100");
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $fbcWidth . "' WHERE PkID = 101");
				break;
			case 3:
				$livefyreID = isset($_POST['livefyreID']) ? cIn($_POST['livefyreID']) : '';
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $livefyreID . "' WHERE PkID = 102");
				break;
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
		if(isset($_POST['twtrRevoke']) || $twtrRevoke == true)
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = NULL WHERE PkID IN (46,47,63,64)");
		if(isset($_POST['fbRevoke']) || $fbRevoke == true)
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = NULL WHERE PkID IN (119,120,121,122,123)");
		
		clearCache();
		
		header("Location: " . AdminRoot . "/index.php?com=apiset&msg=1");
	} else {
		header("Location: " . AdminRoot . "/index.php?com=apiset");
	}
?>