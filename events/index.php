<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
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

<table cellpadding="0" cellspacing="0" border="0" width="600" height="100%">
	<tr>
		<td width="450" valign="top" style="padding:10px;" class="main" bgcolor="white">
				
				<table cellpadding="5" cellspacing="0" border="0">
					<tr>
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
		<td width="150" valign="top" align="center">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><?include(HC_Controls);?></td>
				</tr>
			</table>
			
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="150" class="main" align="center" valign="top">
						<b>Billboard</b><br>
						<?	include(HC_Billboard);?>
					</td>
				</tr>
				<tr><td><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td width="150" class="main" align="center" valign="top">
						<b>Most Popular</b><br>
						<?	include(HC_Popular);?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>