<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	
	include('includes/header.php');
?>
</head>
<body>
<div id="header"><?php echo CalName;?> Mobile</div>
<div id="content">
	<ul>
		<li><a href="<?php echo MobileRoot;?>/browse.php"><?php echo $hc_lang_mobile['TodayEvent'];?></a></li>
		<li><a href="<?php echo MobileRoot;?>/weekly.php"><?php echo $hc_lang_mobile['WeekEvent'];?></a></li>
		<li><a href="<?php echo MobileRoot;?>/search.php"><?php echo $hc_lang_mobile['Search'];?></a></li>
		<li><a href="<?php echo MobileRoot;?>/about.php"><?php echo $hc_lang_mobile['AboutOur'];?></a></li>
		<li><a href="<?php echo MobileRoot;?>/lang.php"><?php echo $hc_lang_mobile['Language'];?></a></li>
		<li><a href="<?php echo MobileRoot;?>/help.php"><?php echo $hc_lang_mobile['Help'];?></a></li>
	</ul>
</div>
<?php
	include('includes/footer.php');?>
</body>
</html>