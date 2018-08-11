<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('../events/includes/headerAdmin.php');
?>
	
	<title><?echo CalName;?></title>
</head>

<body<?if(!isset($_SESSION['AdminLoggedIn'])){echo " onLoad=\"document.frm.username.focus();\"";}//end if?>>

<?php
	if(isset($_SESSION['AdminLoggedIn'])){
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="topMenu">&nbsp;</td>
		<td width="780" valign="top" class="topMenu" height="21">
			
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td colspan="3" width="780" class="main"><?include(HC_AdminMenu);?></td>
				</tr>
				<tr>
					<td width="600" class="main" style="padding-left:10px;padding-top:15px;" valign="top">
						<span style="color: #222222;"><?echo date("l \\t\h\e jS \of F Y");?></span><br><br>
						<?include(HC_Core);?>
					</td>
					<td width="10" class="main">&nbsp;</td>
					<td width="170" class="main" valign="top" align="center">
						<img src="<?echo CalAdminRoot;?>/images/logo.gif" width="125" height="38" alt="" border="0">
						<br><br>
						<a href="<?echo CalRoot;?>/" class="main" target="_blank"><b>Open Public Calendar</b></a>
						<br><br>
						<?php
							include(HC_SideStats);
						?>
					</td>
				</tr>
			</table>
			
		</td>
		<td class="topMenu">&nbsp;</td>
	</tr>
</table>
		
<?
	} else {
?>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td valign="middle" align="center" class="main">
						<br><br><br><br><br>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="180" height="200" align="center" class="main" style="background-color: #EFEFEF; border: solid #BDBDBD 1px;">
									<img src="<?echo CalAdminRoot;?>/images/keys.gif" width="78" height="92" alt="" border="0"><br>
									<div align="left" style="padding: 3px;">
										Enter a valid username and password to gain access to the administration console.<br><br>
										If you need assistance contact the <a href="mailto:<?echo CalAdminEmail;?>?subject=<?echo CalName;?> Administration Question" class="main">site administrator</a>.
									</div>
								</td>
								<td class="main" width="210" valign="middle" align="right" style="background-color: #EFEFEF; border-top: solid #BDBDBD 1px; border-bottom: solid #BDBDBD 1px; border-right: solid #BDBDBD 1px; padding: 3px;">
								<br>
								<?php 
										if (isset($_GET['msg'])){
											switch ($_GET['msg']){
												case "1" :
													feedback(2,"Invalid Login.");
													break;
												
												case "2" :
													feedback(2,"Incorrect Password.");
													break;
											}
										}//end if
									?>
								<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_Login;?>">
								<input type="hidden" name="com" id="com" value="<?if(!isset($_GET['com'])){echo "home";}else{echo $_GET['com'];}//end if?>">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td class="main" align="right">Username:&nbsp;</td>
											<td><input size="20" maxlength="100" type="text" name="username" id="username" value="<?if(isset($_GET['username'])){echo $_GET['username'];}//end if?>" class="input">&nbsp;</td>
										</tr>
										<tr><td colspan="2" class="main"></td></tr>
										<tr>
											<td class="main">Password:&nbsp;</td>
											<td align="left"><input size="15" maxlength="15" type="password" name="password" id="password" value="" class="input">&nbsp;</td>
										</tr>
										<tr><td colspan="2" class="main"></td></tr>
										<tr>
											<td class="main">&nbsp;</td>
											<td align="left"><input type="submit" name="submit" id="submit" value="   Login   " class="button"></td>
										</tr>
									</table>
								</form>
									
								</td>
							</tr>
						</table>
						<br><br>
					</td>
				</tr>
			</table>	
			
<?
	}//end if
?>
				
	<div class="footer" align="center">
	Helios Calendar <?echo HC_Version;?><br>
	Copyright &copy; 2004-<?echo date("Y");?><br>
	<a href="http://www.refreshwebdev.com" class="copyright" target="_blank">Refresh Web Development LLC</a><br>
	ALL RIGHTS RESERVED
	<br><br>
	</div>	
<script src="<?echo CalRoot;?>/includes/java/wz_tooltip.js" type="text/JavaScript"></script>
</body>
</html>
