<?php
	include('../../events/includes/include.php');
	hookDB();
	
	$maxDisplay = $_POST['maxRSS'];
	$allowsubmit = $_POST['allowsubmit'];
	$driving = $_POST['driving'];
	$weather = $_POST['weather'];
	$mostPopular = $_POST['mostPopular'];
	$browsePast = $_POST['browsePast'];
	$state = $_POST['locState'];
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($allowsubmit) . "' WHERE PkID = 1");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($maxDisplay) . "' WHERE PkID = 2");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($driving) . "' WHERE PkID = 8");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($weather) . "' WHERE PkID = 9");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($mostPopular) . "' WHERE PkID = 10");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($browsePast) . "' WHERE PkID = 11");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($state) . "' WHERE PkID = 21");
	
	header("Location: " . CalAdminRoot . "/index.php?com=generalset&msg=1");
?>