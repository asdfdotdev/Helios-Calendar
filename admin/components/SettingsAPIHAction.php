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
	
	$api_on = (isset($_POST['api_onoff']) && is_numeric($_POST['api_onoff'])) ? cIn($_POST['api_onoff']) : '';
	$api_cache = (isset($_POST['api_cache']) && is_numeric($_POST['api_cache'])) ? cIn($_POST['api_cache']) : '0';
	$api_event_size = (isset($_POST['event_size']) && is_numeric($_POST['event_size'])) ? cIn($_POST['event_size']) : '25';
	$api_news_size = (isset($_POST['news_size']) && is_numeric($_POST['news_size'])) ? cIn($_POST['news_size']) : '25';
	
	if($api_on != ''){
		DoQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($api_on, 127));
		
		if($api_on == 1){
			DoQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($api_cache, 128));
			DoQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($api_event_size, 129));
			DoQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($api_news_size, 132));
			
			if($api_cache == 2){
				$api_user_limit = (isset($_POST['apc_size']) && is_numeric($_POST['apc_size'])) ? cIn($_POST['apc_size']) : '25';
				$api_user_time = (isset($_POST['apc_time']) && is_numeric($_POST['apc_time'])) ? cIn($_POST['apc_time']) : '60';
				
				DoQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($api_user_limit, 130));
				DoQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($api_user_time, 131));
			}
		}
		
		clearCache();
		
		header("Location: " . AdminRoot . "/index.php?com=api&msg=1");
	} else {
		header("Location: " . AdminRoot . "/index.php?com=api");
	}
?>