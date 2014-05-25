<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	
	action_headers();
	
	if(user_check_status() || $hc_cfg[114] == 0 || isset($_GET['error']))
		go_home();
	
	$target = CalRoot.'/index.php';
	$callback_url = CalRoot.'/signin/facebook.php';
	$app_id = $app_secret = '';
	$_SESSION['FB_State'] = (!isset($_SESSION['FB_State'])) ? md5(mt_rand().date("U")) : $_SESSION['FB_State'];
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(117,118)");
	if(hasRows($result)){
		$app_id = mysql_result($result,0,0);
		$app_secret = mysql_result($result,1,0);
	}
	
	if($app_id != '' && $app_secret != ''){
		if(isset($_GET['state']) && isset($_GET['code'])){
			if(!isset($_SESSION['FB_State']) || $_SESSION['FB_State'] != $_GET['state']){
				session_destroy();
				go_home();
			}
			
			$code = cIn(strip_tags($_GET['code']));
			$response = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id='.$app_id.'&redirect_uri='.urlencode($callback_url).'&client_secret='.$app_secret.'&code='.$code);
			$params = null;
			parse_str($response, $params);
			$user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token='.$params['access_token']));
			
			if(isset($user) && isset($params)){
				$result = doQuery("SELECT PkID, Email, Birthdate FROM " . HC_TblPrefix . "users WHERE NetworkType = '2' AND NetworkID = '" . cIn($user->id) . "'");

				if(!hasRows($result)){
					$local_id = user_register_new(2,$user->name,$user->id);
					$_SESSION['new_user'] = true;
					$_SESSION['new_user_bday'] = $user->birthday;
					$_SESSION['new_user_email'] = $user->email;
				} else {
					$local_id = mysql_result($result,0,0);
					if(mysql_result($result,0,1) == '' || mysql_result($result,0,2) == ''){
						$_SESSION['new_user'] = true;
						$_SESSION['new_user_bday'] = $user->birthday;
						$_SESSION['new_user_email'] = $user->email;
					}
				}

				$_SESSION['UserNetToken'] = $params['access_token'];
				$_SESSION['UserNetSecret'] = NULL;

				user_update_status(2, $user->name, $user->id, 1);
				user_update_history($local_id);
				unset($_SESSION['FB_State']);

				$target = CalRoot . '/index.php?com=acc';
			}
		} else {
			$target = 'https://www.facebook.com/dialog/oauth?client_id='.urlencode($app_id).'&redirect_uri='.urlencode($callback_url).'&state='.urlencode($_SESSION['FB_State']).'&display=page&scope='.urlencode('email,user_birthday');
		}
	}
	
	header('Location: ' . $target);
?>
