<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('../../events/includes/headerAdmin.php');
?>
<style type="text/css">
.panel{ 
	padding: 5px;
	}
</style>
	<script language="JavaScript" type="text/javascript">
      var panels = new Array('credits', 'thankyou', 'license', 'registration');
      function showPanel(who)
      {
        for(i = 0; i < panels.length; i++)
        {
          document.getElementById(panels[i]).style.display = (who == panels[i]) ? 'block':'none';
        }
        return false;
      }
    </script>
	<title>About Helios <?echo HC_Version;?></title>
</head>
<body onBlur="self.focus()" bgcolor="#EFEFEF">
<table cellpadding="0" border="0" width="100%">
	<tr>
		<td class="eventMain" class="eventMain">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="eventMain" class="eventMain" valign="top" align="center">
						<img src="<?echo CalAdminRoot;?>/images/aboutLogo.jpg" width="150" height="150" alt="" border="1">
					</td>
					<td width="5">&nbsp;</td>
					<td class="eventMain" class="eventMain" valign="top" align="left">
						<div align="right" style="padding-top:5px;padding-bottom:7px;"><a href="javascript:;" class="main" onclick="javascript:parent.window.focus();top.window.close()">Close Window</a>&nbsp;</div>
						<table border="0">				
						<tr>
							<td class="eventMain" class="eventMain"><b>Version:</b>&nbsp;</td>
							<td class="eventMain" class="eventMain">Helios <?echo HC_Version;?></td>
						</tr>
						<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="3" alt="" border="0"></td></tr>
						<tr>
							<td class="eventMain" class="eventMain"><b>Website:</b>&nbsp;</td>
							<td class="eventMain" class="eventMain"><a href="http://www.helioscalendar.com" class="main" target="_blank">www.HeliosCalendar.com</a></td>
						</tr>
						<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="3" alt="" border="0"></td></tr>
						<tr>
							<td class="eventMain" class="eventMain"><b>Author:</b>&nbsp;</td>
							<td class="eventMain" class="eventMain">Christopher L. Carlevato</td>
						</tr>
						<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="3" alt="" border="0"></td></tr>
						<tr>
							<td class="eventMain" valign="top"><b>Copyright:</b>&nbsp;</td>
							<td class="eventMain">Copyright &copy; 2004-<?echo date("Y");?><br><a href="http://www.refreshwebdev.com" class="main" target="_blank">Refresh Web Development</a></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="3" alt="" border="0"></td></tr>
				<tr>
					<td colspan="3" class="main" align="center">
						<a href="javascript:;" onclick="return showPanel('credits');" class="main">Credits</a> | 
						<a href="javascript:;" onclick="return showPanel('thankyou');" class="main">Thank You</a> | 
						<a href="javascript:;" onclick="return showPanel('license');" class="main">License</a> | 
						<a href="javascript:;" onclick="return showPanel('registration');" class="main">Registration</a>
					</td>
				</tr>
				<tr>
					<td class="eventMain" colspan="3" valign="top">
						
						<div class="panel" id="credits">
							
							<b>Credits</b>
							<table cellspacing="1" cellpadding="1" border="0" style="padding-left: 10px;">
								<tr>
									<td colspan="2" class="main">
										Thank you to the following individuals for developing outstanding<br>
										components, and making them available so they could be used <br>
										in Helios.
										<br><br>
									</td>
								</tr>
								<tr>
									<td width="160" class="eventMain"><a href="http://tinymce.moxiecode.com/" class="main" target="_blank">Moxie Code</a>&nbsp;&nbsp;</td>
									<td class="eventMain">Tiny MCE</td>
								</tr>
								<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
								<tr>
									<td class="eventMain"><a href="http://www.phpclasses.org/browse/package/1372.html" class="main" target="_blank">Rick Hopkins</a></td>
									<td class="eventMain">phpCal</td>
								</tr>
								<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
								<tr>
									<td class="eventMain"><a href="http://www.walterzorn.com/tooltip/tooltip_e.htm" class="main" target="_blank">Walter Zorn</a></td>
									<td class="eventMain">wz_tooltip</td>
								</tr>
								<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
								<tr>
									<td class="eventMain"><a href="http://www.mattkruse.com/javascript/" class="main" target="_blank">Matt Kruse</a></td>
									<td class="eventMain">JavaScript Date Picker</td>
								</tr>
								<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
								<tr>
									<td class="eventMain"><a href="http://www.iconbuffet.com/" class="main" target="_blank">IconBuffet</a></td>
									<td class="eventMain">Icons By</td>
								</tr>
							</table>
							
						</div>
						<div class="panel" id="thankyou" style="display: none">
							
							<b>Thank You</b>
							<table cellspacing="1" cellpadding="1" border="0" style="padding-left: 10px;">
								<tr>
									<td colspan="2" class="main">
										Helios wouldn't be what it is without the following people.<br>
										Sincerest thanks to them for their help along the way.
										<br><br>
									</td>
								</tr>
								<tr>
									<td width="120" class="eventMain"><a href="http://kjake.net" class="main" target="_blank">Kevin Jacobson</a></td>
									<td class="eventMain">Useability, Bug Hunting &amp; Moral Support.</td>
								</tr>
								<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
								<tr>
									<td class="eventMain">Josh Depenbrok</td>
									<td class="eventMain">Documentation, Naming &amp; Gramar Police.</td>
								</tr>
								<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
								<tr>
									<td class="eventMain"><a href="http://sw17ch.com" class="main" target="_blank">John Van Enk</a></td>
									<td class="eventMain">Platform Testing &amp; Server Guru</td>
								</tr>
								<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
								<tr>
									<td class="eventMain">Matt Meersman</td>
									<td class="eventMain">For believing in what could be.</td>
								</tr>
							</table>
							
						</div>
						<div class="panel" id="license" style="display: none">
							<iframe src="<?echo CalRoot;?>/docs/license.html" width="100%" height="210" frameborder="0"></iframe>
						</div>
						<div class="panel" id="registration" style="display: none">
							
							<b>Registration</b>
							<br><br>
							<table cellspacing="1" cellpadding="1" border="0" style="padding-left: 10px;">
							<?	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(16,17,18,19) ORDER BY PkID");	?>
								<tr>
									<td width="120" class="eventMain">Registered To</td>
									<td class="eventMain"><?echo cOut(mysql_result($result,0,0));?></td>
								</tr>
								<tr>
									<td width="120" class="eventMain">Email Address</td>
									<td class="eventMain"><?echo cOut(mysql_result($result,1,0));?></td>
								</tr>
								<tr>
									<td width="120" class="eventMain">Registered Domain</td>
									<td class="eventMain"><?echo cOut(mysql_result($result,2,0));?></td>
								</tr>
								<tr>
									<td width="120" class="eventMain">Registration Code</td>
									<td class="eventMain"><?echo cOut(mysql_result($result,3,0));?></td>
								</tr>
							<table>
							
						</div>
					</td>
				</tr>
			</table>
			
		</td>
	</tr>
</table>