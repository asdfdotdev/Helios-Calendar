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
	
	$fullsite = isset($_POST['fullsite']) ? cIn(strip_tags($_POST['fullsite'])) : 'default';
	$mobile = isset($_POST['mobile']) ? cIn(strip_tags($_POST['mobile'])) : 'mobile';
	$agents = isset($_POST['agents']) ? cIn($_POST['agents']) : '//';
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $fullsite . "' WHERE PkID = 83");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $mobile . "' WHERE PkID = 84");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $agents . "' WHERE PkID = 86");
	
	clearCache();

	header("Location: " . AdminRoot . "/index.php?com=themes&msg=1");
?>