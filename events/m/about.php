<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
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