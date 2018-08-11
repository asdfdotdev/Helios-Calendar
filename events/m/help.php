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
	
	include('includes/header.php');?>
</head>
<body>
<div id="header"><?php echo $hc_lang_mobile['Help'];?></div>
<div id="menu">
	<a href="<?php echo MobileRoot;?>/">&laquo; <?php echo $hc_lang_mobile['Home'];?></a>
</div>
<div id="about">
	<b><?php echo $hc_lang_mobile['Accesskeys'];?></b><br/>
	<?php echo $hc_lang_mobile['AccesskeysHelp'];?>
	
	<br/><br/>
	<b>1</b> - <?php echo $hc_lang_mobile['TodayEvent'];?><br/>
	<b>2</b> - <?php echo $hc_lang_mobile['WeekEvent'];?><br/>
	<b>3</b> - <?php echo $hc_lang_mobile['Search'];?><br/>
	<b>4</b> - <?php echo $hc_lang_mobile['Language'];?><br/>
	<b>0</b> - <?php echo $hc_lang_mobile['HelpFile'];?>
	
	<br/><br/>
	<b><?php echo $hc_lang_mobile['Search'];?></b><br/>
	<?php echo $hc_lang_mobile['SearchHelp'];?>
	
	
	<br/><br/>
	<b><?php echo $hc_lang_mobile['FullSite'];?></b><br/>
	<?php echo $hc_lang_mobile['FullSiteHelp'];?>
	<a href="<?php echo CalRoot;?>" style="font-size:x-small;"><?php echo CalRoot;?>/</a>
</div>
<?php
	include('includes/footer.php');?>
</body>
</html>
