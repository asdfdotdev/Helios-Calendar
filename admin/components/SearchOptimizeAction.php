<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	$indexing = $_POST['indexing'];
	$keywords = $_POST['keywords'];
	$description = $_POST['description'];
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($keywords) . "' WHERE PkID = 5");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($description) . "' WHERE PkID = 6");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($indexing) . "' WHERE PkID = 7");
	
	header("Location: " . CalAdminRoot . "/index.php?com=optimize&msg=1");
?>