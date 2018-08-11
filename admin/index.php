<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
	
	NOTE: You may not alter any logos, legends, branding elements or copyrights withing the admin console (/admin)
	unless you have been granted express written permission by Refresh Web Development to do so.
	The "Standard License" does not include this permission, a "Developer License" is required for such permission.
	Visit: http://www.helioscalendar.com/developers.php for details.
*/
	include('includes/header.php');
?>
	<title><?php echo CalName;?></title>
</head>

<body<?php if(!isset($_SESSION[$hc_cfg00 . 'AdminLoggedIn'])){echo " onload=\"document.frm.elements[0].focus();\"";}//end if?>>

<?php
	if(isset($_SESSION[$hc_cfg00 . 'AdminLoggedIn'])){	?>
	<div id="container">
		<div id="menu"><?php include(HC_AdminMenu);?>&nbsp;</div>
		<div id="content">
			<div id="cMain">
				<div style="color: #222222; padding: 4px 0px 7px 0px;"><?php echo strftime("%A, %d %B %Y");?></div>
		<?php 	include(HC_Core);?>
			</div>
			<div id="cSub">
				<img src="<?php echo CalAdminRoot;?>/images/logo.png" width="200" height="49" alt="" border="0" />
				<br /><br />
				<a href="<?php echo CalRoot;?>/" class="main" target="_blank"><b><?php echo $hc_lang_menu['Link'];?></b></a>
			</div>
		</div>
<?php
	} else {
		echo "<div>";
		include(HC_Login);
	}//end if	?>
	
	<div class="footer" align="center">
		Helios Calendar <?php echo $hc_cfg49;?><br />
		Copyright &copy; 2004-<?php echo date("Y");?><br />
		<a href="http://www.refreshmy.com" class="copyright" target="_blank">Refresh Web Development, LLC.</a><br />
		ALL RIGHTS RESERVED
		<br /><br />
	</div>
	</div>
<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/wz_tooltip.js"></script>
</body>
</html>
