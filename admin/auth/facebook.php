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
	
	$app_id = $app_secret = $fb_token = $fb_expires = '';
	$s_token = $l_token = null;
	$target = AdminRoot.'/index.php';
	$callback_url = AdminRoot.'/auth/facebook.php';
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(117,118)");
	if(hasRows($result)){
		$app_id = mysql_result($result,0,0);
		$app_secret = mysql_result($result,1,0);
	}
	
	if($app_id != '' && $app_secret != '' && !isset($_GET['error']) && $_SESSION['APIAuth'] == 1){
		$_SESSION['FB_State'] = (!isset($_SESSION['FB_State'])) ? md5(mt_rand().date("U")) : $_SESSION['FB_State'];
		
		if(isset($_GET['state']) && isset($_GET['code'])){
			if(!isset($_SESSION['FB_State']) || $_SESSION['FB_State'] != $_GET['state']){
				session_destroy();
				go_home();
			}
			$code = cIn(strip_tags($_GET['code']));
			
			$response = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id='.$app_id.'&redirect_uri='.urlencode($callback_url).'&client_secret='.$app_secret.'&code='.$code);
			parse_str($response, $s_token);
			$response = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id='.$app_id.'&client_secret='.$app_secret.'&grant_type=fb_exchange_token&fb_exchange_token='.$s_token['access_token']);
			parse_str($response, $l_token);
			
			if(isset($s_token['expires']) && isset($l_token['expires'])){
				if($s_token['expires'] > $l_token['expires']){
					$fb_token = $s_token['access_token'];
					$fb_expires = $s_token['expires'];
				} else {
					$fb_token = $l_token['access_token'];
					$fb_expires = $l_token['expires'];
				}
			} else {
				$fb_token = ($l_token['access_token'] != '') ? $l_token['access_token'] : $s_token['access_token'];
			}
			
			$fb_expires = ($fb_expires == '') ? '86400' : $fb_expires;
			$user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token='.$fb_token));
			
			if($fb_token == '' || !isset($user) || count($user) == 0){
				$target = AdminRoot . '/index.php?com=apiset&msg=5';
			} else {
				$pages = json_decode(file_get_contents('https://graph.facebook.com/me/accounts?access_token='.$fb_token));
				$pub_opts[] = (object) array('name' => $user->name,'access_token' => $fb_token,'category' => 'User','id' => $user->id);
				
				foreach($pages->data as $page){
					if(strtolower($page->category) != 'application')
						$pub_opts[] = (object) array('name' => $page->name,'access_token' => $page->access_token,'category' => $page->category,'id' => $page->id);
					
				}
				
				if(count($pub_opts) > 0)
					doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn(utf8_decode(json_encode($pub_opts,JSON_FORCE_OBJECT)),0) . "' WHERE PkID = '119'");
				if($fb_expires != '')
					doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn(date("U") + $fb_expires) . "' WHERE PkID = '122'");
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($fb_token) . "' WHERE PkID = '120'");
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($fb_token) . "' WHERE PkID = '121'");	
				doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($user->id) . "' WHERE PkID = '123'");
				
				unset($_SESSION['FB_State']);
				
				$target = AdminRoot . '/index.php?com=apiset&msg=4';
			}
		} else {
			$target = 'https://www.facebook.com/dialog/oauth?client_id='.urlencode($app_id).'&redirect_uri='.urlencode($callback_url).'&state='.urlencode($_SESSION['FB_State']).'&display=page&scope='.urlencode('user_status,publish_stream,manage_pages,create_event,user_events');
		}
	}
	
	header('Location: ' . $target);
?>
