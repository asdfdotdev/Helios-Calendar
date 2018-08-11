<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
	
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying Helios Setup files is not permitted and violates the Helios EUL.	|
	|	Please do not edit this or any of the setup files							|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	$setup = true;
	include('../includes/include.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta name="robots" content="noindex, nofollow">
	<meta name="GOOGLEBOT" content="noindex, nofollow">
	<meta http-equiv="generator" content="Refresh Web Calendar">
	<meta http-equiv="author" content="Refresh Web Development LLC">
	<meta http-equiv="email" content="<?echo CalAdminEmail;?>">
	<meta http-equiv="copyright" content="2004-<?echo date("Y");?> All Rights Reserved">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="description" content="Helios Setup">
	<meta name="MSSmartTagsPreventParsing" content="yes">
	
	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/helios.css">
	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/setup/style.css">
	<link rel="icon" href="<?echo CalRoot;?>/setup/images/favicon.png" type="image/png">
	<link rel="alternate" type="application/rss+xml" title="<?echo CalName;?> RSS 2.0 Event Feed" href="<?echo CalRoot;?>/rss.php" />
	
<?php
	include('../includes/java/javaInclude.php');
	
	if(isset($_GET['step']) && is_numeric($_GET['step'])){
		$sID = $_GET['step'];
	} else {
		$sID = 1;
	}//end if
?>
<title>Helios <?echo HC_Version;?> Setup</title>


<body>
<table cellpadding="0" cellspacing="0" border="0" width="780" height="100%">
	<tr>
		<td bgcolor="#F0F0EE" style="padding-top:10px;padding-left:7px;" width="180" valign="top" align="center">
			<img src="<?echo CalRoot;?>/template/logo.gif" width="202" height="51" alt="" border="0"><br><br>
			<table cellpadding="0" cellspacing="0" border="0" width="200">
				<tr>
					<td class="eventMain">
						<a href="http://codex.helioscalendar.com/index.php?title=Setup" class="main" target="new">Setup Documentation</a>
						<br><img src="<?echo CalRoot;?>/setup/images/spacer.gif" width="1" height="7" alt="" border="0"><br>
						<a href="http://codex.helioscalendar.com/" class="main" target="new">Helios Codex</a>
						<br><img src="<?echo CalRoot;?>/setup/images/spacer.gif" width="1" height="7" alt="" border="0"><br>
						<a href="http://forum.helioscalendar.com/" class="main" target="new">Helios Support Forum</a>
						<br><img src="<?echo CalRoot;?>/setup/images/spacer.gif" width="1" height="10" alt="" border="0"><br><br>
							<a href="http://www.helioscalendar.com/" class="main" target="_blank">Helios Version</a><br>
							&nbsp;&nbsp;&nbsp;&nbsp;<b><?echo HC_Version;?></b>
						<br><br>
							<a href="http://www.php.net/" class="main" target="_blank">PHP Version</a><br>
							&nbsp;&nbsp;&nbsp;&nbsp;<b><?echo phpversion();?></b>
						<br><br>
							<?	if(isset($_SESSION['mysqlversion'])){	?>
								<a href="http://www.mysql.com/" class="main" target="_blank">MySQL Version</a><br>
								&nbsp;&nbsp;&nbsp;&nbsp;<b><?echo $_SESSION['mysqlversion'];?></b>
							<?	}//end if	?>
						<br><br>
							Operating System<br>
							&nbsp;&nbsp;&nbsp;&nbsp;<b><?echo $_SERVER['OS'];?></b>
						<br><br>
							Web Server<br>
							&nbsp;&nbsp;&nbsp;&nbsp;<b><?echo $_SERVER['SERVER_SOFTWARE'];?></b>
					</td>
				</tr>
			</table>
		</td>
		<td width="2" bgcolor="#CCCCCC"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td>
		<td width="2" bgcolor="#EFEFEF"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td>
		<td width="370" valign="top" style="padding:10px;" class="main">
				
			<div style="font-size:14px;font-weight:bold;" align="right">Step <span style="color:green;"><?echo $sID;?></span> of 4</div>	
		<?	switch($sID){
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
			<br><br><br><br><br><br>
			Helios Calendar <?echo HC_Version;?> &copy; Copyright 2004-<?echo date("Y");?><br>
			<a href="http://www.refreshwebdev.com" class="copyright" target="_blank">Refresh Web Development LLC</a> ALL RIGHTS RESERVED
		</td>
	</tr>
</table>
<script src="<?echo CalRoot;?>/includes/java/wz_tooltip.js" type="text/JavaScript"></script>
</body>
</html>