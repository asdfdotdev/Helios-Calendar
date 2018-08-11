<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('includes/include.php');
	
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (5,6,7,11,21) ORDER BY PkID");
	if(hasRows($result)){
		$keywords = cOut(mysql_result($result,0,0));
		$description = cOut(mysql_result($result,1,0));
		$allowindex = cOut(mysql_result($result,2,0));
		$browsePast = cOut(mysql_result($result,3,0));
		$defaultState = cOut(mysql_result($result,4,0));
	} else {
		exit(handleError(0, "Helios Settings Data Missing. You will need to run Helios Setup again."));
	}//end if
	
	define("HC_Menu", "components/Menu.php");
	define("HC_Core", "components/Core.php");
	define("HC_Controls", "components/ControlPanel.php");
	define("HC_Billboard", "components/Billboard.php");
	define("HC_Popular", "components/Popular.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php
	if($allowindex == 1){?>
	<meta name="robots" content="all, index, follow">
	<meta name="GOOGLEBOT" content="index, follow">
<?} else {?>
	<meta name="robots" content="noindex, nofollow">
	<meta name="GOOGLEBOT" content="noindex, nofollow">
<?}//end if?>
	<meta http-equiv="author" content="Refresh Web Development LLC">
	<meta http-equiv="email" content="<?echo CalAdminEmail;?>">
	<meta http-equiv="copyright" content="2004-<?echo date("Y");?> All Rights Reserved">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="description" content="<?echo $description;?>">
	<meta http-equiv="keywords" content="<?echo $keywords;?>">
	<meta http-equiv="expires" content="604800">
	<meta name="MSSmartTagsPreventParsing" content="yes">
	<meta name="geo.country" content="US">
	
	<link rel="bookmark" title="<?echo CalName;?>" href="<?echo CalRoot;?>">
	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/helios.css">
	<link rel="icon" href="<?echo CalRoot;?>/images/favicon.png" type="image/png">
	<link rel="alternate" type="application/rss+xml" title="<?echo CalName;?> RSS 2.0 Event Feed" href="<?echo CalRoot;?>/rss.php" />
	
	<meta name="generator" content="Helios Calendar <?echo HC_Version;?>"> <!-- leave this for stats -->
	
<?php
	include('java/javaInclude.php');
?>
