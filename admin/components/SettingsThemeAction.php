<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	post_only();
	
	$fullsite = isset($_POST['fullsite']) ? cIn(strip_tags($_POST['fullsite'])) : 'default';
	$mobile = isset($_POST['mobile']) ? cIn(strip_tags($_POST['mobile'])) : 'mobile';
	$agents = isset($_POST['agents']) ? cIn($_POST['agents']) : '//';
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $fullsite . "' WHERE PkID = 83");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $mobile . "' WHERE PkID = 84");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $agents . "' WHERE PkID = 86");
	
	clearCache();

	header("Location: " . AdminRoot . "/index.php?com=themes&msg=1");
?>