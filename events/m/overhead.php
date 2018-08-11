<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (14,23,28) ORDER BY PkID");
	$hc_dateFormat = cOut(mysql_result($result,0,0));
	$hc_timeFormat = cOut(mysql_result($result,1,0));
	$hc_langType = cOut(mysql_result($result,2,0));
	if(!isset($_SESSION['LangSet'])){
		$_SESSION['LangSet'] = $hc_langType;
	}//end if
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/mobile.php');
	$loc = setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);
?>