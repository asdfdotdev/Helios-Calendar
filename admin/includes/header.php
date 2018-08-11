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
	ini_set("include_path",ini_get('include_path').";".dirname($_SERVER['SCRIPT_FILENAME']));
	
	include('includes/include.php');
	
	if(file_exists('setup/') || file_exists('../events/setup')){
	?>	<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/admin.css">
		<div style="width:60%;"><?php feedback(3,"You must delete the setup directory before using the Helios admin.<br />For security this message is not displayed on the public calendar.");?></div><?php
		exit;
	}//end if
	clearstatcache();
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (14,21,23,24,28,30,31,35) ORDER BY PkID");
	if(hasRows($result)){
		$hc_dateFormat = mysql_result($result,0,0);
		$hc_defaultState = mysql_result($result,1,0);
		$hc_timeFormat = cOut(mysql_result($result,2,0));
		$hc_popDateFormat = cOut(mysql_result($result,3,0));
		$hc_WYSIWYG = cOut(mysql_result($result,5,0));
		$hc_timezoneOffset = cOut(mysql_result($result,7,0));
		$hc_langType = cOut(mysql_result($result,4,0));
		if(!isset($_SESSION['LangSet'])){
			$_SESSION['LangSet'] = $hc_langType;
		}//end if
		
		$hc_timeInput = 23;
		if(cOut(mysql_result($result,6,0)) == 12){
			$hc_timeInput = 12;
		}//end if
	} else {
		exit(handleError(0, "Helios Settings Data Missing. You will need to run Helios Setup again."));
	}//end if
	
	define("HC_AdminMenu", "components/AdminMenu.php");
	define("HC_Core", "components/Core.php");
	define("HC_LogOut", "components/LogOut.php");
	define("HC_SideStats", "components/SideStats.php");
	define("HC_InstructionsSwitch", "components/InstructionSwitch.php");
	define("HC_Login", "components/Login.php");
	
	switch($hc_popDateFormat){
		case '%m/%d/%Y':
			$hc_popDateValid = "MM/dd/yyyy";
			break;
			
		case '%d/%m/%Y':
			$hc_popDateValid = "dd/MM/yyyy";
			break;
			
		case '%Y/%m/%d':
			$hc_popDateValid = "yyyy/MM/dd";
			break;
	}//end switch
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/menu.php');
	include($hc_langPath . $_SESSION['LangSet'] . '/config.php');
	setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="copyright" content="2004-<?php echo date("Y");?> Refresh Web Development All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $hc_lang_config['CharSet'];?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/admin.css" />
	<link rel="icon" href="<?php echo CalRoot;?>/images/favicon.png" type="image/png" />
	<link rel="apple-touch-icon" href="<?php echo CalAdminRoot;?>/images/appleIcon.png" type="image/png" />