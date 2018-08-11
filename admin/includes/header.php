<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	ini_set("include_path",ini_get('include_path').";".dirname($_SERVER['SCRIPT_FILENAME']));
	
	include('includes/include.php');
	
	if(file_exists('../events/setup')){
	?>	<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/admin.css">
		<div style="width:60%;"><?php feedback(3,"You must delete the setup directory before using the Helios admin.<br />For security this message is not displayed on the public calendar.");?></div><?php
		exit;
	}//end if
	clearstatcache();
	
	$hc_timeInput = cOut($hc_cfg31) == 12 ? 12 : 23;
	
	define("HC_AdminMenu", "components/AdminMenu.php");
	define("HC_Core", "components/Core.php");
	define("HC_LogOut", "components/SignOut.php");
	define("HC_InstructionsSwitch", "components/InstructionSwitch.php");
	define("HC_Login", "components/Login.php");
		
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/menu.php');
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/config.php');
	setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $hc_lang_config['HTMLTemplate'];?>" lang="<?php echo $hc_lang_config['HTMLTemplate'];?>">
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="copyright" content="2004-<?php echo date("Y");?> Refresh Web Development All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $hc_lang_config['CharSet'];?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/admin.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/dateSelect.css" />
	<link rel="icon" href="<?php echo CalRoot;?>/images/favicon.png" type="image/png" />
	<link rel="apple-touch-icon" href="<?php echo CalAdminRoot;?>/images/appleIcon.png" type="image/png" />