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
	
	$indexing = $_POST['indexing'];
	$keywords = $_POST['keywords'];
	$description = $_POST['description'];
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($keywords) . "' WHERE PkID = 5");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($description) . "' WHERE PkID = 6");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($indexing) . "' WHERE PkID = 7");
	
	header("Location: " . CalAdminRoot . "/index.php?com=optimize&msg=1");
?>