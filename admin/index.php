<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('../events/includes/headerAdmin.php');
?>
	<title><?echo CalName;?></title>
</head>

<body<?if(!isset($_SESSION['AdminLoggedIn'])){echo " onload=\"document.frm.elements[0].focus();\"";}//end if?>>

<?	if(isset($_SESSION['AdminLoggedIn'])){	?>
	<div id="container">
		<div id="menu"><?include(HC_AdminMenu);?>&nbsp;</div>
		<div id="content">
			<div id="cMain">
				<div style="color: #222222; padding: 4px 0px 7px 0px;"><?echo date("l \\t\h\e jS \of F Y");?></div>
				<?include(HC_Core);?>
			</div>
			<div id="cSub">
				<img src="<?echo CalAdminRoot;?>/images/logo.gif" width="200" height="50" alt="" border="0" />
				<br /><br />
				<a href="<?echo CalRoot;?>/" class="main" target="_blank"><b>Open Public Calendar</b></a>
			</div>
		</div>
<?	} else {
		include(HC_Login);
	}//end if	?>
		<div class="footer" align="center">
			Helios Calendar <?echo HC_Version;?><br />
			Copyright &copy; 2004-<?echo date("Y");?><br />
			<a href="http://www.refreshwebdev.com" class="copyright" target="_blank">Refresh Web Development LLC</a><br />
			ALL RIGHTS RESERVED
			<br /><br />
		</div>
	</div>
<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/wz_tooltip.js"></script>
</body>
</html>
