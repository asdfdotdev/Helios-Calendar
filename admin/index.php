<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('includes/header.php');
?>
	<title><?php echo CalName;?></title>
</head>

<body<?php if(!isset($_SESSION['AdminLoggedIn'])){echo " onload=\"document.frm.elements[0].focus();\"";}//end if?>>

<?php
	if(isset($_SESSION['AdminLoggedIn'])){	?>
	<div id="container">
		<div id="menu"><?php include(HC_AdminMenu);?>&nbsp;</div>
		<div id="content">
			<div id="cMain">
				<div style="color: #222222; padding: 4px 0px 7px 0px;"><?php echo date("l \\t\h\e jS \of F Y");?></div>
		<?php 	include(HC_Core);?>
			</div>
			<div id="cSub">
				<img src="<?php echo CalAdminRoot;?>/images/logo.png" width="200" height="50" alt="" border="0" />
				<br /><br />
				<a href="<?php echo CalRoot;?>/" class="main" target="_blank"><b>Open Public Calendar</b></a>
			</div>
		</div>
<?php
	} else {
		include(HC_Login);
	}//end if	?>
		<div class="footer" align="center">
			Helios Calendar <?php echo HC_Version;?><br />
			Copyright &copy; 2004-<?php echo date("Y");?><br />
			<a href="http://www.refreshmy.com" class="copyright" target="_blank">Refresh Web Development, LLC.</a><br />
			ALL RIGHTS RESERVED
			<br /><br />
		</div>
	</div>
<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/wz_tooltip.js"></script>
</body>
</html>
