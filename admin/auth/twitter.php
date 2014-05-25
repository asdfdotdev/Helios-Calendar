<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	define('SAFE_REFER',true);
	
	include('../loader.php');
		
	admin_logged_in();
	action_headers();
	
	$target = AdminRoot.'/index.php';
	$callback_url = AdminRoot.'/auth/twitter.php';
	$consumer_key = $consumer_secret = '';
		
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(111,112)");
	if(hasRows($result)){
		$consumer_key = mysql_result($result,0,0);
		$consumer_secret = mysql_result($result,1,0);
	}
	
	if($consumer_key != '' && $consumer_secret != '' && !isset($_GET['denied']) && $_SESSION['APIAuth'] == 1){
		if(isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])){
			$oauth_token = cIn(strip_tags($_GET['oauth_token']));
			$oauth_verifier = cIn(strip_tags($_GET['oauth_verifier']));

			include(HCPATH.HCINC.'/api/twitter/AccessToken.php');

			if(!isset($authUser) || !isset($authUserID) || !isset($authToken) || !isset($authSecret) || $authUser.$authUserID.$authToken.$authSecret == ''){
				$target = AdminRoot . '/index.php?com=apiset&msg=3';
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $authUser . "' WHERE PkID = 63");
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $authUserID . "' WHERE PkID = 64");
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $authToken . "' WHERE PkID = 46");
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $authSecret . "' WHERE PkID = 47");

				$target = AdminRoot . '/index.php?com=apiset&msg=2';
			}
		} else {
			$oauth_token = $oauth_verifier = '';

			include(HCPATH.HCINC.'/api/twitter/RequestToken.php');

			$target = 'https://twitter.com/oauth/authorize?oauth_token='.$_SESSION['RequestToken'];
		}
	}
	
	header('Location: ' . $target);
?>
