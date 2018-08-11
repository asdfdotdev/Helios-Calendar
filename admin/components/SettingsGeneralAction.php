<?php
	include('../../events/includes/include.php');
	hookDB();
	
	$maxDisplay = $_POST['maxRSS'];
	$allowsubmit = $_POST['allowsubmit'];
	$defaultApprove = $_POST['defaultApprove'];
	$defaultDecline = $_POST['defaultDecline'];
	$driving = $_POST['driving'];
	$weather = $_POST['weather'];
	$mostPopular = $_POST['mostPopular'];
	$browsePast = $_POST['browsePast'];
	$state = $_POST['locState'];
	
	$maxShow = $_POST['display'];
	$fillMax = $_POST['fill'];
	$layout = $_POST['dateformat'];
	
	if(isset($_POST['showtime'])){
		$showTime = 1;
	} else {
		$showTime = 0;
	}//end if
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($allowsubmit) . "' WHERE PkID = 1");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($maxDisplay) . "' WHERE PkID = 2");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($defaultApprove) . "' WHERE PkID = 3");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($defaultDecline) . "' WHERE PkID = 4");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($driving) . "' WHERE PkID = 8");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($weather) . "' WHERE PkID = 9");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($mostPopular) . "' WHERE PkID = 10");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($browsePast) . "' WHERE PkID = 11");
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($maxShow) . "' WHERE PkID = 12;");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($fillMax) . "' WHERE PkID = 13;");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($layout) . "' WHERE PkID = 14;");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($showTime) . "' WHERE PkID = 15;");
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($state) . "' WHERE PkID = 21");
	
	header("Location: " . CalAdminRoot . "/index.php?com=generalset&msg=1");
?>