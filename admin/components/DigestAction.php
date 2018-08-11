<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$token = (isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : '';
	if(!check_form_token($token))
		go_home();
	
	$status = (isset($_POST['status']) && is_numeric($_POST['status'])) ? cIn($_POST['status']) : 0;
	$welcomeMsg = isset($_POST['welcomeMsg']) ? cleanQuotes($_POST['welcomeMsg'],0) : '';
	$newFor = (isset($_POST['newFor']) && is_numeric($_POST['newFor'])) ? cIn($_POST['newFor']) : 0;
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $status . "' WHERE PkID = 97");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($welcomeMsg,0) . "' WHERE PkID = 98");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $newFor . "' WHERE PkID = 99");
	
	clearCache();
	
	header('Location: ' . AdminRoot . '/index.php?com=digest&msg=1');
?>