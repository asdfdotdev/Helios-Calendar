<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	$maxShow = $_POST['display'];
	$fillMax = $_POST['fill'];
	$layout = $_POST['dateformat'];
	
	if(isset($_POST['showtime'])){
		$showTime = 1;
	} else {
		$showTime = 0;
	}//end if
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $maxShow . "' WHERE PkID = 12;");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $fillMax . "' WHERE PkID = 13;");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $layout . "' WHERE PkID = 14;");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $showTime . "' WHERE PkID = 15;");
				
	header("Location: " . CalAdminRoot . "/index.php?com=billboard&msg=1");
?>