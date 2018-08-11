<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('include.php');
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (21) ORDER BY PkID");
	if(hasRows($result)){
		$defaultState = mysql_result($result,0,0);
	} else {
		exit(handleError(0, "Helios Settings Data Missing. You will need to run Helios Setup again."));
	}//end if
	
	define("HC_AdminMenu", "components/AdminMenu.php");
	define("HC_Core", "components/Core.php");
	define("HC_Login", "components/LoginAction.php");
	define("HC_LogOut", "components/LogOut.php");
	define("HC_SideStats", "components/SideStats.php");
	define("HC_InstructionsSwitch", "components/InstructionSwitch.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta name="robots" content="noindex, nofollow">
	<meta http-equiv="author" content="Refresh Web Development LLC">
	<meta http-equiv="email" content="helios@refreshwebdev.com">
	<meta http-equiv="copyright" content="2004-<?echo date("Y");?> All Rights Reserved">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/admin.css">
	<link rel="icon" href="<?echo CalRoot;?>/images/favicon.png" type="image/png">
<?php
	include('java/javaInclude.php');
?>