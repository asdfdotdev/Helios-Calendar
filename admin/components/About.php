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
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="copyright" content="2004-<?php echo date("Y");?> Refresh Web Development All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/admin.css" />
	<link rel="icon" href="<?php echo CalRoot;?>/images/favicon.png" type="image/png" />
	<style type="text/css">
	.panel {
		font-family: Verdana, sans-serif;
		font-size: 11px;
		padding: 0px 5px 0px 10px;
		}
	a.egg {
		color: #EFEFEF;
		}
	a.egg:hover {
		text-decoration: none;
		color: #FF6600;
		}
	</style>
	<script language="JavaScript" type="text/javascript">
	//<!--
	window.onblur = function(){self.focus();}
	
	var panels = new Array('credits', 'license', 'registration');
	function showPanel(who){
		for(i = 0; i < panels.length; i++)	{
			document.getElementById(panels[i]).style.display = (who == panels[i]) ? 'block':'none';
		}
		return false;
	}//end showPanel()
	//-->
	</script>
	<title>About Helios <?php echo HC_Version;?></title>
</head>
<body bgcolor="#EFEFEF">

	<div style="float:left;width:160px;padding:5px 10px 5px 5px;">
		<img src="<?php echo CalAdminRoot;?>/images/logoAbout.png" width="150" height="150" alt="" border="0" />
	</div>
	
	<div style="font-family:Verdana,sans-serif;font-size:11px;float:left;width:250px;">
		<div style="padding:5px 5px 10px 5px;text-align:right;"><a href="javascript:;" class="main" onclick="javascript:parent.window.focus();top.window.close()">Close Window</a></div>
		<div style="width:80px;float:left;"><b>Version:</b></div>Helios Calendar <?php echo HC_Version;?>
		<br /><br />
		<div style="width:80px;float:left;"><b>Website:</b></div><a href="http://www.helioscalendar.com" class="main" target="_blank">www.HeliosCalendar.com</a>
		<br /><br />
		<div style="width:80px;float:left;"><b>Author:</b></div>Christopher L. Carlevato&nbsp;&nbsp;<a href="javascript:;" class="egg">&nbsp;:)&nbsp;</a>
		<br /><br />
		<div style="width:80px;float:left;"><b>Copyright:</b></div> Copyright &copy; 2004-<?php echo date("Y");?><br />
		<div style="width:80px;float:left;">&nbsp;</div><a href="http://www.refreshmy.com" class="main" target="_blank">Refresh Web Development</a>
	</div>
	
	<div style="clear:both;">
		<div style="text-align:center;padding: 5px 0px 5px 0px;">
			<a href="javascript:;" onclick="return showPanel('credits');" class="main">Credits</a> | 
			<a href="javascript:;" onclick="return showPanel('license');" class="main">License</a> | 
			<a href="javascript:;" onclick="return showPanel('registration');" class="main">Registration</a>
		</div>
		<div class="panel" id="credits">
			<b>Credits</b>
			<br /><br />
			Thank you to the following for developing outstanding
			components, libraries &amp; services and making them available for use in Helios Calendar.
			<br /><br />
			<div style="padding-bottom:7px;"><div style="width:150px;float:left;padding-left:20px;"><a href="http://api.eventful.com/" class="main" target="_blank">Eventful, Inc.</a></div>Web Services: Eventful.com&reg; Publishing</div>
			<div style="padding-bottom:7px;"><div style="width:150px;float:left;padding-left:20px;"><a href="http://code.google.com/apis/maps/" class="main" target="_blank">Google</a></div>Web Services: Maps &amp; Geocoding</div>
			<div style="padding-bottom:7px;"><div style="width:150px;float:left;padding-left:20px;"><a href="http://twitter.com/help/api" class="main" target="_blank">twitter</a></div>Web Services: twitter Publishing</div>
			<div style="padding-bottom:7px;"><div style="width:150px;float:left;padding-left:20px;"><a href="http://tinymce.moxiecode.com/" class="main" target="_blank">Moxie Code</a></div><img src="<?php echo CalAdminRoot;?>/images/logos/tinymce_button.png" border="0" width="80" height="15" alt="Powered by TinyMCE" /></div>
			<div style="padding-bottom:7px;"><div style="width:150px;float:left;padding-left:20px;"><a href="http://www.javascripttoolbox.com/lib/calendar/" class="main" target="_blank">Matt Kruse</a></div>JavaScript Date Picker</div>
			<div style="padding-bottom:7px;"><div style="width:150px;float:left;padding-left:20px;"><a href="http://www.walterzorn.com/tooltip/tooltip_e.htm" class="main" target="_blank">Walter Zorn</a></div>wz_tooltip</div>
			<div style="padding-bottom:7px;"><div style="width:150px;float:left;padding-left:20px;"><a href="http://www.famfamfam.com/" class="main" target="_blank">FamFamFam</a></div>Silk Icons By</div>
		</div>
		<div class="panel" id="license" style="display: none;">
			<iframe src="<?php echo CalRoot;?>/docs/license.html" width="100%" height="220" frameborder="0" style="background:#EFEFEF;"></iframe>
		</div>
		<div class="panel" id="registration" style="display: none;">
			<b>Registration</b>
	<?php 	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(16,17,18,19) ORDER BY PkID");	?>
			<br /><br />
			<div style="width:130px;float:left;padding-left:20px;">Registrant:</div><?php echo cOut(mysql_result($result,0,0));?>
			<br /><br />
			<div style="width:130px;float:left;padding-left:20px;">Email Address:</div><?php echo cOut(mysql_result($result,2,0));?>
			<br /><br />
			<div style="width:130px;float:left;padding-left:20px;">Registered Domain:</div><?php echo cOut(mysql_result($result,1,0));?>
			<br /><br />
			<div style="width:130px;float:left;padding-left:20px;">Registration Code:</div><?php echo cOut(mysql_result($result,3,0));?>
		</div>
	</div>
</body>
</html>