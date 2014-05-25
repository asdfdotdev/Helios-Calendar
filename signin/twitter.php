<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	
	action_headers();
	
	if(user_check_status() || $hc_cfg[113] == 0 || isset($_GET['denied']))
		go_home();
	
	$target = CalRoot.'/index.php';
	$callback_url = CalRoot.'/signin/twitter.php';
	$consumer_key = $consumer_secret = '';
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(111,112)");
	if(hasRows($result)){
		$consumer_key = mysql_result($result,0,0);
		$consumer_secret = mysql_result($result,1,0);
	}
	
	if($consumer_key != '' && $consumer_secret != ''){
		if(isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])){
			$oauth_token = cIn(strip_tags($_GET['oauth_token']));
			$oauth_verifier = cIn(strip_tags($_GET['oauth_verifier']));

			if(!isset($_SESSION['RequestToken']) || $_SESSION['RequestToken'] != $oauth_token){
				session_destroy();
				go_home();
			}
			
			include(HCPATH.HCINC.'/api/twitter/AccessToken.php');

			if(isset($authUser) && isset($authUserID) && isset($authToken) && isset($authSecret) && $authUser.$authUserID.$authToken.$authSecret != ''){
				$result = doQuery("SELECT PkID, Email, Birthdate FROM " . HC_TblPrefix . "users WHERE NetworkType = '1' AND NetworkID = '" . cIn($authUserID) . "'");
				
				if(!hasRows($result)){
					$local_id = user_register_new(1,$authUser,$authUserID);
					$_SESSION['new_user'] = true;
				} else {
					$local_id = mysql_result($result,0,0);
					if(mysql_result($result,0,1) == '' || mysql_result($result,0,2) == '')
						$_SESSION['new_user'] = true;
				}
				
				$_SESSION['UserNetToken'] = $authToken;
				$_SESSION['UserNetSecret'] = $authSecret;
				user_update_status(1, $authUser, $authUserID, 1);
				user_update_history($local_id);
				
				$target = CalRoot . '/index.php?com=acc';
			}
		} else {
			$oauth_token = $oauth_verifier = '';

			include(HCPATH.HCINC.'/api/twitter/RequestToken.php');
			
			if(isset($_SESSION['RequestToken']) && $_SESSION['RequestToken'] != '')
				$target = 'https://api.twitter.com/oauth/authenticate?oauth_token='.$_SESSION['RequestToken'];
		}
	}
	
	header('Location: ' . $target);
?>
