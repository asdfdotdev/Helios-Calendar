<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
?>
	<br />
	<div align="center">
		<img src="<?php echo CalAdminRoot;?>/images/logoAbout.png" width="260" height="59" style="align:center;" />
	</div>
	
	<br /><br />
	<fieldset>
		<legend>About Helios Calendar</legend>
		<div class="frmOpt">
			<label><b>Developer:</b></label>
			<div style="line-height:20px"><a href="http://www.refreshmy.com" class="main" target="_blank">Refresh Web Development, LLC.</a></div>
		</div>
		<div class="frmOpt">
			<label><b>Copyright:</b></label>
			<div style="line-height:20px">Copyright &copy; 2004-<?php echo date("Y");?>, All Rights Reserved</div>
		</div>
		<div class="frmOpt">
			<label><b>Website:</b></label>
			<div style="line-height:20px"><a href="http://www.helioscalendar.com" class="main" target="_blank">www.HeliosCalendar.com</a></div>
		</div>
	</fieldset>
	
	<br /><br />
	<fieldset>
		<?php $result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(16,17,18,19) ORDER BY PkID");	?>
		<legend>About This Install</legend>
		<div class="frmOpt">
			<label><b>Version:</b></label>
			<div style="line-height:20px">Helios Calendar <?php echo $hc_cfg49;?></div>
		</div>
		<div class="frmOpt">
			<label><b>Licensed To:</b></label>
			<div style="line-height:20px"><?php echo cOut(mysql_result($result,0,0));?> (<?php echo cOut(mysql_result($result,2,0));?>)</div>
		</div>
		<div class="frmOpt">
			<label><b>Licensed For:</b></label>
			<div style="line-height:20px"><?php echo cOut(mysql_result($result,1,0));?></div>
		</div>
		<div class="frmOpt">
			<label><b>License Code:</b></label>
			<div style="line-height:20px"><?php echo cOut(mysql_result($result,3,0));?></div>
		</div>
		<div class="frmOpt">
			<label><b>PHP Version:</b></label>
			<div style="line-height:20px"><?php echo phpversion();?> (<a href="<?php echo CalAdminRoot;?>/components/AboutPHP.php" target="_blank" class="main">About PHP</a>)</div>
		</div>
		<div class="frmOpt">
			<label><b>MySQL Version:</b></label>
			<?php $result = doQuery("SELECT VERSION()");?>
			<div style="line-height:20px"><?php echo mysql_result($result,0,0);?></div>
		</div>
	</fieldset>
	
	<br /><br />
	<fieldset>
		<legend>Credits &amp; Thank You's</legend>
		Thank you to the following for developing outstanding components, libraries &amp; services and making them available for use in Helios Calendar.
		<br /><br />
		For more information, click a name to be taken to their website.
		<br /><br />
		<div class="frmOpt">
			<label><b><a href="http://api.eventful.com/" class="main" target="_blank">Eventful, Inc.</a></b></label>
			<div style="line-height:18px;">Eventful.com&reg; Publishing API</div>
		</div>
		<div class="frmOpt">
			<label><b><a href="http://code.google.com/apis/maps/" class="main" target="_blank">Google</a></b></label>
			<div style="line-height:20px;">Google Maps &amp; Geocoding</div>
		</div>
		<div class="frmOpt">
			<label><b><a href="http://twitter.com/help/api" class="main" target="_blank">twitter</a></b></label>
			<div style="line-height:20px;">twitter Publishing API</div>
		</div>
		<div class="frmOpt">
			<label><b><a href="http://tinymce.moxiecode.com/" class="main" target="_blank">Moxie Code</a></b></label>
			<img src="<?php echo CalAdminRoot;?>/images/logos/tinymce_button.png" border="0" width="80" height="15" alt="Powered by TinyMCE" />
		</div>
		<div class="frmOpt">
			<label><b><a href="http://openidenabled.com/php-openid/" class="main" target="_blank">JanRain, Inc.</a></b></label>
			<div style="line-height:20px;">PHP OpenID Library</div>
		</div>
		<div class="frmOpt">
			<label><b><a href="http://www.javascripttoolbox.com/lib/calendar/" class="main" target="_blank">Matt Kruse</a></b></label>
			<div style="line-height:20px;">JavaScript Date Picker</div>
		</div>
		<div class="frmOpt">
			<label><b><a href="http://www.walterzorn.com/tooltip/tooltip_e.htm" class="main" target="_blank">Walter Zorn</a></b></label>
			<div style="line-height:20px;">wz_tooltip</div>
		</div>
		<div class="frmOpt">
			<label><b><a href="http://www.famfamfam.com/" class="main" target="_blank">FamFamFam</a></b></label>
			<div style="line-height:20px;">Silk Icon Collection</div>
		</div>
	</fieldset>
	
	<br /><br />
	<fieldset>
		<legend>Helios Calendar Software License Agreement</legend>
		<iframe src="<?php echo CalRoot;?>/docs/license.html" width="100%" height="300" frameborder="0"></iframe>
	</fieldset>