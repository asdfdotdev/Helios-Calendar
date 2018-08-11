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
	
	$status = (isset($_POST['status']) && is_numeric($_POST['status'])) ? cIn($_POST['status']) : 0;
	$welcomeMsg = isset($_POST['welcomeMsg']) ? cleanQuotes($_POST['welcomeMsg'],0) : '';
	$newFor = (isset($_POST['newFor']) && is_numeric($_POST['newFor'])) ? cIn($_POST['newFor']) : 0;
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $status . "' WHERE PkID = 97");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($welcomeMsg,0) . "' WHERE PkID = 98");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $newFor . "' WHERE PkID = 99");
	
	clearCache();
	
	header('Location: ' . AdminRoot . '/index.php?com=digest&msg=1');
?>