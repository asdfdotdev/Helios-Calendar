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
	include('includes/header.php');
?>
	<title><?php echo CalName;?> - Helios Calendar Event Management System</title>
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/template/style.css" />
</head>

<body>
<?php
/*	To Skin Helios to your sites layout edit anything in the <body> tag of this page
	as well as the stylesheet included above located in your (CalRoot)/template/ directory
	It is recomended you store images for your skin in the template directory with the stylesheet.
	
	Include the following
	
	include(HC_Menu) ------	Helios Menu
	include(HC_Core) ------	Primary Content
	include(HC_Controls) --	Control Panel
	include(HC_Billboard) -	Billboard
	include(HC_Popular) ---	Most Popular Events
*/	?>
<br />
<div id="container">
	<div id="content">
	<?php
		include(HC_Menu);
		echo "<br />";
		include(HC_Core);
		?>
	</div>
	<div id="controls">
		<div id="language">
		<?php include(HC_Language);?>
		</div>
	<?php
		include(HC_Controls);
		include(HC_Links);
		?>
		<div id="billboard">
			<img src="<?php echo CalRoot;?>/template/billboard.gif" width="180" height="40" alt="Helios Billboard Events" />
			<?php include(HC_Billboard);?>
		</div>
		
		<div id="popular">
			<img src="<?php echo CalRoot;?>/template/popular.gif" width="180" height="40" alt="Helios Most Popular Events" />
			<?php include(HC_Popular);?>
		</div>
	</div>
	<div id="copyright">
		<a href="http://www.helioscalendar.com" class="copyright">Helios Calendar <?php echo HC_Version;?></a> Copyright 2004-<?php echo date("Y");?> <a href="http://www.refreshmy.com" class="copyrightR">Refresh Web Development</a> | <a href="http://validator.w3.org/check?uri=referer" target="_blank" class="copyright">XHTML 1.0</a> - <a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php echo urlencode(CalRoot . "/css/helios.css");?>" target="_blank" class="copyright">CSS 2.1</a>
		<br />
	</div>
</div>
<?php 
/*
	NOTE: This include must be the last thing in your template before the
	close body tag. If it is not your Google Maps will not work.
*/
	include(HC_GMap);?>
</body>
</html>