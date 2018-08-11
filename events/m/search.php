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
<div id="header"><?php echo $hc_lang_mobile['Search'];?></div>
<div id="menu">
	<a href="<?php echo MobileRoot;?>/">&laquo;&nbsp;<?php echo $hc_lang_mobile['Home'];?></a> &#124;
	<a href="<?php echo MobileRoot;?>/browse.php" class="mnu"><?php echo $hc_lang_mobile['Browse'];?></a>
</div>
<div id="search">
	<form name="frm" id="frm" method="post" action="<?php echo MobileRoot;?>/results.php">
		<?php echo $hc_lang_mobile['SearchText'];?><br/>
		<input type="text" name="srchText" id="srchText" value="" class="txtInput"/><br/>
		<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_mobile['Submit'];?>" class="btnInput"/>
	</form>
</div>
<?php
	include('includes/footer.php');?>
</body>
</html>