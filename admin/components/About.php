<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('../../events/includes/include.php');
	checkIt(1);
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="copyright" content="2004-<?echo date("Y");?> Refresh Web Development All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/admin.css" />
	<link rel="icon" href="<?echo CalRoot;?>/images/favicon.png" type="image/png" />
	
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
	var panels = new Array('credits', 'thankyou', 'license', 'registration');
	function showPanel(who){
		for(i = 0; i < panels.length; i++)	{
			document.getElementById(panels[i]).style.display = (who == panels[i]) ? 'block':'none';
		}
		return false;
	}
	//-->
	</script>
	<title>About Helios <?echo HC_Version;?></title>
</head>
<body onblur="self.focus();" bgcolor="#EFEFEF">

	<div style="float:left;width:150px;padding:5px 10px 5px 5px;">
		<img src="<?echo CalAdminRoot;?>/images/aboutLogo.jpg" width="150" height="150" alt="" border="1" />
	</div>
	
	<div style="font-family:Verdana,sans-serif;font-size:11px;float:left;width:260px;">
		<div style="padding:5px 5px 10px 5px;text-align:right;"><a href="javascript:;" class="main" onclick="javascript:parent.window.focus();top.window.close()">Close Window</a></div>
		
		<div style="width:80px;float:left;"><b>Version:</b></div>Helios <?echo HC_Version;?>
		
		<br /><br />
		<div style="width:80px;float:left;"><b>Website:</b></div><a href="http://www.helioscalendar.com" class="main" target="_blank">www.HeliosCalendar.com</a>
		
		<br /><br />
		<div style="width:80px;float:left;"><b>Author:</b></div>Christopher L. Carlevato&nbsp;&nbsp;<a href="javascript:;" class="egg">&nbsp;:)&nbsp;</a>
		
		<br /><br />
		<div style="width:80px;float:left;"><b>Copyright:</b></div> Copyright &copy; 2004-<?echo date("Y");?><br />
		<div style="width:80px;float:left;">&nbsp;</div><a href="http://www.refreshwebdev.com" class="main" target="_blank">Refresh Web Development</a>
	</div>
	
	<div style="clear:both;">
		<div style="text-align:center;padding: 5px 0px 5px 0px;">
			<a href="javascript:;" onclick="return showPanel('credits');" class="main">Credits</a> | 
			<a href="javascript:;" onclick="return showPanel('thankyou');" class="main">Thank You</a> | 
			<a href="javascript:;" onclick="return showPanel('license');" class="main">License</a> | 
			<a href="javascript:;" onclick="return showPanel('registration');" class="main">Registration</a>
		</div>
		<div class="panel" id="credits">
			<b>Credits</b>
			
			<br /><br />
			Thank you to the following individuals for developing outstanding
			components, and making them available so they could be used in Helios.
			
			<br /><br />
			<div style="width:100px;float:left;padding-left:20px;"><a href="http://tinymce.moxiecode.com/" class="main" target="_blank">Moxie Code</a></div>Tiny MCE
			<br /><br />
			<div style="width:100px;float:left;padding-left:20px;"><a href="http://www.walterzorn.com/tooltip/tooltip_e.htm" class="main" target="_blank">Walter Zorn</a></div>wz_tooltip
			<br /><br />
			<div style="width:100px;float:left;padding-left:20px;"><a href="http://www.mattkruse.com/javascript/" class="main" target="_blank">Matt Kruse</a></div>JavaScript Date Picker
		</div>
		<div class="panel" id="thankyou" style="display: none">
			<b>Thank You</b>
			
			<br /><br />
			Helios wouldn't be what it is without the following people.
			<br />
			Sincerest thanks to them for their help along the way.
			
			<br /><br />
			<div style="width:100px;float:left;padding-left:20px;"><a href="http://kjake.net" class="main" target="_blank">Kevin Jacobson</a></div>Testing and Forum Support
			<br /><br />
			<div style="width:100px;float:left;padding-left:20px;">Matt Meersman</div>For believing in what could be.
		</div>
		<div class="panel" id="license" style="display: none">
			<iframe src="<?echo CalRoot;?>/docs/license.html" width="100%" height="200" frameborder="0" style="background:#EFEFEF;"></iframe>
		</div>
		<div class="panel" id="registration" style="display: none">
			<b>Registration</b>
			
		<?	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(16,17,18,19) ORDER BY PkID");	?>
			<br /><br />
			<div style="width:130px;float:left;padding-left:20px;">Registrant:</div><?echo cOut(mysql_result($result,0,0));?>
			<br /><br />
			<div style="width:130px;float:left;padding-left:20px;">Email Address:</div><?echo cOut(mysql_result($result,2,0));?>
			<br /><br />
			<div style="width:130px;float:left;padding-left:20px;">Registered Domain:</div><?echo cOut(mysql_result($result,1,0));?>
			<br /><br />
			<div style="width:130px;float:left;padding-left:20px;">Registration Code:</div><?echo cOut(mysql_result($result,3,0));?>
		</div>
	</div>
</body>
</html>