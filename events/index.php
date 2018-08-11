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
	
	Powered by images are available in your (CalRoot)/images/powered/ directory.
*/
?>
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
		<br />
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
		
		<br />
		<div id="valid">
			<img src="<?php echo CalRoot;?>/template/valid.gif" width="88" height="31" alt="Helios Validates XHTML 1.0 and CSS 2.0" />
		</div>
		
	</div>
	<div id="copyright">
		<a href="http://www.helioscalendar.com" class="copyright">Helios Calendar <?php echo HC_Version;?></a> Copyright 2004-<?php echo date("Y");?> <a href="http://www.refreshwebdev.com" class="copyrightR">Refresh Web Development</a>
	</div>
</div>
<?php include(HC_GMap);?>
</body>
</html>