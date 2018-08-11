<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html

	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying Helios Setup files is not permitted and violates 				|
	|	the Helios Calendar SLA. DO NOT edit this or any of the setup files.	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	session_start();
	
	$curVersion = "1.6.1";
	$setup = true;
	if(file_exists('../includes/globals.php')){
		include('../includes/globals.php');
	}//end if
	include('../includes/code.php');
	$sID = (isset($_GET['step']) && is_numeric($_GET['step'])) ? $_GET['step'] : 1;
	
	function fail(){
		echo '<br />Helios Calendar is unable to continue the Web Setup Wizard for one of the following reasons:<ol><li>Sessions are not working correctly for your PHP install.</li><li>You\'re trying to skip ahead without following each step in order.</li></ol>';
		echo '<a href="index.php" class="main">Click here to restart the Helios Calendar Web Setup Wizard.</a><br />If you see this message again submit a support ticket by clicking the "Refresh Members Site" link to the right.';
	}//end fail()?>
	
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta name="GOOGLEBOT" content="noindex, nofollow" />
	<meta http-equiv="generator" content="Helios Calendar" />
	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="copyright" content="2004-<?php echo date("Y");?> All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="description" content="Helios Setup" />
	<meta name="MSSmartTagsPreventParsing" content="yes" />
	<link rel="icon" href="../images/favicon.png" type="image/png" />
	<style type="text/css">
		html, body {margin: 0;padding: 0;background:#F5F6F7;color: #000000;font-family: Verdana, sans-serif;font-size: 11px;}
		h2{font-size:13px;padding:0px;padding:0px;text-transform:uppercase;letter-spacing:3px}
		#menu {text-align:center;width:100%;height:25px;}
		#container {margin:auto auto auto auto;width:980px;padding:0px;color:#000000;}
		#content {float:left;text-align:left;padding:5px 5px 15px 5px;width:72%;background:#FFFFFF;border:solid 1px #666666;min-height:450px;padding:}
		#controls {float:left;padding:0px 5px 5px 5px;width:25%;}
		#categories{float:left;width:25%;}
		#core{float:left;width:70%;}
		#language {text-align:center;padding:10px 0 15px 0;}
		#billboard,#popular {text-align:left;padding:10px 0 10px 0;}
		#rssLinks {width:760px;padding:0;margin:auto auto auto auto ;}
		#copyright {clear:both;color:#666666;padding:5px 0px 5px 0px;line-height:17px;}
		a.copyright,a.copyrightR {text-decoration:none;color:#666666;}
		a.copyright:hover {text-decoration:underline;color:#FF6600;}
		a.copyrightR:hover {text-decoration:underline;color:#006532;}
		.software{clear:both;float:left;width:130px;font-weight:bold;padding:0px 0px 10px 0px;}
		.version{float:left;width:110px;padding:0px 0px 10px 0px;}
		a.main{text-decoration:underline;color:#3D3F3E;}
		a.main:hover{text-decoration:none;color:#FF6600;}
		a.side{text-decoration:underline;font-size:11.5px;line-height:25px;color:#3D3F3E;padding:0px 0px 0px 10px;}
		a.side:hover{text-decoration:none;color:#FF6600;}
		fieldset{clear:both;border:0px;border-bottom:solid 1px #CCCCCC;}
		legend{font-size:12px;font-weight:bold;color:#000000;background:transparent;margin:1px;padding:0px 10px 2px 0px;}
		label{float:left;width:150px;padding:0px 10px 0px 0px;text-align:right;}
		fieldset div{margin-bottom:5px;}
		fieldset div input, textarea, select{font-family:Verdana, sans-serif;font-size:11px;border-top:1px solid #555;border-left:1px solid #555;border-bottom:1px solid #ccc;border-right:1px solid #ccc;padding:1px;color:#333;vertical-align:middle;font-size:11px;}
		fieldset div.frmReq,fieldset div.frmReq input, textarea, select{font-weight:bold;}
		fieldset div.frmOpt,fieldset div.frmOpt input, textarea, select{font-weight:normal;}
		div.frmReq,div.frmOpt{clear:both;}
		.textbox{}
		.inactive{color:#ACACAC;padding:0px 3px 0px 3px;}
		.active{font-size:11px;color:crimson;padding:0px 3px 0px 3px;}
		.button{font-size:11px;min-width:150px;}
		.info{clear:both;font-weight:bold;padding:3px;text-align:center;background:#eef7ef;border: solid 2px #008E0D;}
		.warning{clear:both;font-weight:bold;padding:3px;text-align:center;background:#fffcee;border: solid 2px #FFDA00;}
		.error{clear:both;font-weight:bold;padding:3px;text-align:center;background:#fceeee;border: solid 2px #E40000;}
	</style>
	<title>Helios Calendar <?php echo $curVersion;?> Setup</title>
<body>
<?php
	if(phpversion() < 5){
		echo '<br /><div class="error" style="width:990px;margin:auto;">It appears you\'re using an unsupported version of PHP. If you continue the setup will not be successful and support will not<br />be available from Refresh Web Development. You should upgrade to PHP5 immediately, <a href="http://www.refreshmy.com/documentation/?title=Server_Requirements" class="main" target="_blank">PHP4 is NO LONGER SUPPORTED.</a></div><br />';
	}//end if

	if(!file_exists('../includes/globals.php')){
		$noGlobal = true;
		echo '<br /><div class="warning" style="width:990px;margin:auto;">globals.php File Not Found. Setup Cannot Be Performed.</div><br />';
	}//end if	?>
	<br />
	<div id="container">
		<div id="content">
		<br />
		<div style="font-size:11px;font-weight:bold;border-bottom:solid 1px #CCCCCC;padding:0px 0px 10px 0px;color:#000;">
			Steps:
			<span class="<?php echo ($sID == 1) ? 'active' : 'inactive';?>">Accept License</span> &raquo;
			<span class="<?php echo ($sID == 2) ? 'active' : 'inactive';?>">globals.php</span> &raquo;
			<span class="<?php echo ($sID == 3) ? 'active' : 'inactive';?>">Enter License Code</span> &raquo;
			<span class="<?php echo ($sID == 4) ? 'active' : 'inactive';?>">First Admin &amp; Database Setup</span> &raquo;
			<span class="<?php echo ($sID == 0) ? 'active' : 'inactive';?>">Finished</span>
		</div>
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
			case 0:
				include('finished.php');
				break;
			case 1:
			default:
				include('step1.php');
				break;
		}//end switch	?>
		</div>
		<div id="controls">
			<h2>Need Help?</h2>
			<a href="http://www.refreshmy.com/documentation/?title=Setup" class="side" target="new">Setup Documentation</a><br />
			<a href="http://www.refreshmy.com/documentation/?title=Helios" class="side" target="new">Helios Calendar Documentation</a><br />
			<a href="https://www.refreshmy.com/members/" class="side" target="_blank">Refresh Members Site</a><br />
			<a href="http://www.refreshmy.com/forum/" class="side" target="_blank">Refresh Community Forum</a><br />
			
			<br />
			<h2>Software Profile</h2>
			
			<div class="software">Helios Calendar</div>
			<div class="version"><?php echo $curVersion;?></div>
			
			<div class="software">PHP</div>
			<div class="version"><?php echo phpversion();?></div>
			
			<div class="software">MySQL</div>
			<div class="version"><?php echo (isset($_SESSION['mysqlversion'])) ? $_SESSION['mysqlversion'] : 'Checking...';?></div>
			
			<div class="software">Operating System</div>
			<div class="version"><?php echo PHP_OS;?></div>
			
			<div class="software">Web Server</div>
			<div class="version"><?php echo $_SERVER['SERVER_SOFTWARE'];?></div>
		</div>
		<div id="copyright">
			<a href="http://www.helioscalendar.com" class="copyright" target="_blank">Helios Calendar <?php echo $curVersion;?></a> Copyright &copy; 2004-<?php echo date("Y");?> <a href="http://www.refreshmy.com" class="copyrightR" target="_blank">Refresh Web Development</a>
		</div>
	</div>
	<script src="../includes/java/wz_tooltip.js" type="text/JavaScript"></script>
</body>
</html>