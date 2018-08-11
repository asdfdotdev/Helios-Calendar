<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('includes/header.php');
	$returnPage = $_SERVER['PHP_SELF'];
?>
	<title><?echo CalName;?> :: Powered by Helios Calendar <?echo HC_Version;?></title>
</head>

<body>
<?
/*	To Skin Helios to your sites layout edit anything in the <body> tag of this page
	as well as the stylesheet included above located in your (CalRoot)/template/ directory
	It is recomended you store images for your skin in the template directory with the stylesheet.
	
	Include the following
	
	include(HC_Menu) ------	Helios Menu
	include(HC_Core) ------	Primary Content
	include(HC_Controls) --	Control Panel
	include(HC_Billboard) -	Billboard
	include(HC_Popular) ---	Most Popular Events
	
	Powered by images are available in your (CalRoot)/images/powered/ directory.
*/
?>

<table cellpadding="0" cellspacing="0" border="0" width="780" height="100%">
	<tr>
		<td bgcolor="#F0F0EE" style="padding-top:10px;padding-left:7px;" width="350" valign="top" align="center">
			<img src="<?echo CalRoot;?>/template/logo.gif" width="202" height="51" alt="" border="0"><br><br>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><?include(HC_Controls);?></td>
					<td valign="middle" width="20" align="right"><?appInstructionsIcon("Control Panel","The Helios control panel allows visitors to navigate events quickly and easily. Want to know what\'s going on in " . date("F", mktime(0,0,0,date("m") + 1,1,date("Y"))) . "? Click the arrow just to the right of \'<i>" . date("F") . " \'" . date("y") . "\'</i> then click any day of the month and you can view the events.<br><br>White boxes show days <b>without</b> events while colored boxes show days <b>with</b> events, the current day is displayed in bold font to allow for a monthly overview at a glance.");?></td>
				</tr>
			</table>
			<br>	
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="150" class="main" align="center" valign="top">
						<?	appInstructionsIcon("Event Billboard","The billboard is a list of events the administrator has highlighted for special promotion. This list can be displayed throughout Helios to allow for additional exposure for special events.");
							echo "<br><br>";
							include(HC_Billboard);?>
					</td>
					<td width="20">&nbsp;</td>
					<td width="150" class="main" align="center" valign="top">
						<?	appInstructionsIcon("Most Popular Events","The most popular events list is just that, the most popular events for your calendar. This list is updated in real time so your visitors can see exactly which of your events are getting the most traffic.");
							echo "<br><br>";
							include(HC_Popular);?>
					</td>
				</tr>
			</table>
		</td>
		<td width="2" bgcolor="#CCCCCC"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td>
		<td width="2" bgcolor="#EFEFEF"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td>
		<td width="426" valign="top" style="padding:10px;" class="main">
				
				<table cellpadding="5" cellspacing="0" border="0">
					<tr>
						<td align="right"><?appInstructionsIcon("Menu & Content", "As with each of the other front end components you can place the menu and content into any layout configuration.");?></td>
						<td bgcolor="#F0F0EE" style="border: solid #666666 1px;" valign="top" align="center">
						<?	//	Include the Helios Menu
							include(HC_Menu);?>
						</td>
					</tr>
				</table>

					<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"><br>

			<?	// Include the Core Helios Content
				include(HC_Core);
			?>
			<br><br><br><br><br><br>
			Helios Calendar <?echo HC_Version;?> &copy; Copyright 2004-<?echo date("Y");?><br>
			<a href="http://www.refreshwebdev.com" class="copyright" target="_blank">Refresh Web Development LLC</a> ALL RIGHTS RESERVED
		</td>
	</tr>
</table>
<!-- The wz_tooltip code can be removed if you do not wish to use it. -->
<script src="<?echo CalRoot;?>/includes/java/wz_tooltip.js" type="text/JavaScript"></script>
<!-- end remove -->
</body>
</html>