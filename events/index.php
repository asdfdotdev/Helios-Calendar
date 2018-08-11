<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.index.html
*/
	include('includes/header.php');
?>
	<title><?php echo CalName;?> - powered by Helios Calendar</title>
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/template/style.css" />
</head>

<body>
<?php
/*	To Skin Helios to your sites layout edit anything in the <body> tag of this page
	as well as the stylesheet included above located in your (CalRoot)/template/ directory
	It is recomended you store images for your skin in the template directory with the stylesheet.
	
	Use the following include statements
	
	include(HC_Login) -----	OpenID Login Form
	include(HC_Menu) ------	Helios Menu
	include(HC_Core) ------	Primary Content
	include(HC_Language) -- Language Select (Flag Icons)
	include(HC_Controls) --	Control Panel
	include(HC_Links) -----	RSS & iCal Subscribe Links
	include(HC_Billboard) -	Billboard
	include(HC_Popular) ---	Most Popular Events
	include(HC_GMap) ------	Google Maps Function (See Note Below)
*/	?>
<div id="container">
	<div id="login"><?php include(HC_Login);?></div>
	<div id="content">
	<?php
		include(HC_Menu);
		include(HC_Core);
		echo '<div style="clear:both;">&nbsp;</div>';
		include(HC_Links);
		?>
	</div>
	<div id="controls">
	<?php
		include(HC_Controls);
		?>
		<div id="language">
			<?php include(HC_Language);?>
		</div>
		<div id="billboard">
			<div class="listHeader">Billboard Events</div>
			<?php include(HC_Billboard);?>
		</div>
		
		<div id="popular">
			<div class="listHeader">Most Popular Events</div>
			<?php include(HC_Popular);?>
		</div>
	</div>
	<div id="copyright">
		<a href="http://www.helioscalendar.com" class="copyright">Helios Calendar <?php echo $hc_cfg49;?></a> Copyright &copy; 2004-<?php echo date("Y");?> <a href="http://www.refreshmy.com" class="copyrightR">Refresh Web Development</a> | <a href="http://validator.w3.org/check?uri=referer" target="_blank" class="copyright">XHTML 1.0</a> - <a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php echo urlencode(CalRoot . "/css/helios.css");?>" target="_blank" class="copyright">CSS 2.1</a>
		<br />
	</div>
</div>
<?php 
/*
	NOTE: This include must be the last thing in your template before the
	close body tag. If it is not your Google Maps will not work in some browsers (Namely IE).
*/
	include(HC_GMap);?>
</body>
</html>