<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf

	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying Helios Setup files is not permitted and violates the Helios EUL.	|
	|	Please do not edit this or any of the setup files							|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	$setup = true;
	$isAction = 1;
	include('../includes/include.php');
	
	if(isset($_GET['step']) && is_numeric($_GET['step'])){
		$sID = $_GET['step'];
	} else {
		$sID = 1;
	}//end if	?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta name="GOOGLEBOT" content="noindex, nofollow" />
	<meta http-equiv="generator" content="Refresh Web Calendar" />
	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="email" content="<?php echo CalAdminEmail;?>" />
	<meta http-equiv="copyright" content="2004-<?php echo date("Y");?> All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="description" content="Helios Setup" />
	<meta name="MSSmartTagsPreventParsing" content="yes" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/template/style.css" />
	<link rel="icon" href="<?php echo CalRoot;?>/images/favicon.png" type="image/png" />
<body>
<?php
	if(!file_exists('../includes/globals.php')){
		echo "<div style=\"width: 450px;\">";
		feedback(2,"globals.php File Not Found. Please refer to the setup instructions <a href=\"http://www.helioscalendar.com/documentation/index.php?title=Setup\" class=\"eventMain\" target=\"_blank\">in the documentation</a> for help setting up your globals.php");
		echo "</div>";
		exit();
	}//end if	?>
	<br />
	<div id="container">
		<div id="content">
		<br />
		<div style="font-size:14px;font-weight:bold;border-bottom: solid 1px black;">Step <span style="color:green;"><?php echo $sID;?></span> of 4</div>
<?php	switch($sID){
			case 2:
				include('step2.php');
				break;
				
			case 3:
				include('step3.php');
				break;
				
			case 4:
				include('step4.php');
				break;
				
			case 1:
			default:
				include('step1.php');
				break;
		}//end switch	?>
		</div>
		<div id="controls">
			<br />
			<b>Need Help?</b><br />
			&nbsp;&nbsp;<a href="http://www.refreshmy.com/documentation/?title=Setup" class="eventMain" target="new">Setup Documentation</a><br />
			&nbsp;&nbsp;<a href="http://www.refreshmy.com/documentation/?title=Helios" class="eventMain" target="new">Helios Documentation</a><br />
			&nbsp;&nbsp;<a href="http://www.refreshmy.com/forum/" class="eventMain" target="new">Helios Support Forum</a><br />
			<br />
			<a href="http://www.helioscalendar.com/" class="eventMain" target="_blank">Helios Calendar Version</a><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo HC_Version;?></b>
			<br /><br />
			<a href="http://www.php.net/" class="eventMain" target="_blank">PHP Version</a><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo phpversion();?></b>
			<br /><br />
	<?php	if(isset($_SESSION['mysqlversion'])){	?>
			<a href="http://www.mysql.com/" class="eventMain" target="_blank">MySQL Version</a><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $_SESSION['mysqlversion'];?></b>
			<br /><br />
	<?php	}//end if	?>
			Operating System<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo PHP_OS;?></b>
			<br /><br />
			Web Server<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $_SERVER['SERVER_SOFTWARE'];?></b>
		</div>
		<div id="copyright">
			<a href="http://www.helioscalendar.com" class="copyright" target="_blank">Helios Calendar <?php echo HC_Version;?></a> Copyright 2004-<?php echo date("Y");?> <a href="http://www.refreshmy.com" class="copyrightR" target="_blank">Refresh Web Development</a>
		</div>
	</div>
	<script src="<?php echo CalRoot;?>/includes/java/wz_tooltip.js" type="text/JavaScript"></script>
</body>
</html>