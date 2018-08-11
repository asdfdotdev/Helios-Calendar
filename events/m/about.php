<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	include('includes/header.php');	?>
</head>
<body>
<div id="header"><?php echo $hc_lang_mobile['AboutOur'];?></div>
<div id="menu">
	<a href="<?php echo MobileRoot;?>/">&laquo; <?php echo $hc_lang_mobile['Home'];?></a>
</div>
<div id="about">
	This mobile event calendar is powered by <a href="http://www.helioscalendar.com" class="main" target="_blank">Helios Calendar</a>.
	<br/><br/>
	Helios Calendar is a product of <a href="http://www.refreshmy.com" class="main" target="_blank">Refresh Web Development</a>.
</div>
<?php
	include('includes/footer.php');?>
</body>
</html>