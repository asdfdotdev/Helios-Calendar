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
	error_reporting(0);
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}?>
	<br />
	<div align="center">
		<img src="<?php echo CalAdminRoot;?>/images/logoAbout.png" width="260" height="59" alt="" />
	</div>
	<br /><br />
<?php
	$ip = gethostbyname("www.refreshmy.com");
	if($fp = fsockopen($ip, 80, $errno, $errstr, 1)){
		$read = '';
		$request = "GET /_update/h/cur.php HTTP/1.1\r\nHost: www.refreshmy.com\r\nConnection: Close\r\n\r\n";
		fwrite($fp, $request);
		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}//end while
		fclose($fp);

		$output = explode("/bof", $read);
		if(isset($output[1])){
			$output = explode("/eof", $output[1]);
		}//end if

		if($output[0] != '' && $output[0] > $hc_cfg49){
			$hc_Side[] = array('https://www.refreshmy.com/members/','iconInfo.png','Download Version <b>' . $output[0] . '</b>',1);
			feedback(1,'A new version of Helios Calendar is available.');
			echo '<br />';
		}//end if
	}//end if
?>
	<fieldset>
		<legend>About Helios Calendar</legend>
		<div class="frmOpt">
			<label><b>Developer:</b></label>
			<div style="line-height:15px"><a href="http://www.refreshmy.com" class="main" target="_blank">Refresh Web Development, LLC.</a></div>
		</div>
		<div class="frmOpt">
			<label><b>Copyright:</b></label>
			<div style="line-height:15px">Copyright &copy; 2004-<?php echo date("Y");?>, All Rights Reserved</div>
		</div>
		<div class="frmOpt">
			<label><b>Website:</b></label>
			<div style="line-height:15px"><a href="http://www.helioscalendar.com" class="main" target="_blank">www.HeliosCalendar.com</a></div>
		</div>
	</fieldset>
	<br /><br />
	<fieldset>
		<?php $result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(16,17,18,19) ORDER BY PkID");	?>
		<legend>About This Install</legend>
		<div class="frmOpt">
			<label><b>Version:</b></label>
			<div style="line-height:15px">Helios Calendar <?php echo $hc_cfg49;?></div>
		</div>
		<div class="frmOpt">
			<label><b>Licensed To:</b></label>
			<div style="line-height:15px"><?php echo cOut(mysql_result($result,0,0));?> (<?php echo cOut(mysql_result($result,2,0));?>)</div>
		</div>
		<div class="frmOpt">
			<label><b>Licensed For:</b></label>
			<div style="line-height:15px"><?php echo cOut(mysql_result($result,1,0));?></div>
		</div>
		<div class="frmOpt">
			<label><b>License Code:</b></label>
			<div style="line-height:15px"><?php echo cOut(mysql_result($result,3,0));?></div>
		</div>
		<div class="frmOpt">
			<label><b>PHP Version:</b></label>
			<div style="line-height:15px"><?php echo phpversion();?> (<a href="<?php echo CalAdminRoot;?>/components/AboutPHP.php" target="_blank" class="main">About PHP</a>)</div>
		</div>
		<div class="frmOpt">
			<label><b>MySQL Version:</b></label>
			<?php $result = doQuery("SELECT VERSION()");?>
			<div style="line-height:15px"><?php echo mysql_result($result,0,0);?></div>
		</div>
		<div class="frmOpt">
			<label><b>Load Average:</b></label>
		<?php
			$load = array();
			if(function_exists('exec')){
				$load = strtolower(exec("uptime"));
				$load = explode('load average: ',$load);
			}//end if
			echo '<div style="line-height:15px">';
			echo isset($load[1]) && $load[1] != '' ? $load[1] : 'Unavailable';
			echo ' (<a href="http://en.wikipedia.org/wiki/Load_%28computing%29" target="_blank" class="main">What is This?</a>)</div>';?>
		</div>
	</fieldset>
	<br /><br />
	<fieldset>
		<legend>Credits &amp; Thank You's</legend>
		Refresh Web Development would like to thank the following businesses and individuals for developing outstanding components, libraries &amp; services and making them available for use in Helios Calendar.
		<br /><br />
		For more information, click a link below to be taken to their website.
		<br /><br />
		<div style="width:46%;float:left;">
		<div class="frmOpt">
			<label><b><a href="http://code.google.com/p/bitly-api/wiki/ApiDocumentation" class="main" target="_blank">bit.ly, Inc.</a></b></label>
			<div style="line-height:18px;">bit.ly API</div>
		</div>
		<div class="frmOpt">
			<label><b><a href="http://developer.eventbrite.com/" class="main" target="_blank">Eventbrite</a></b></label>
			<div style="line-height:15px;">Eventbrite API</div>
		</div>
		<div class="frmOpt">
			<label><b><a href="http://api.eventful.com/" class="main" target="_blank">Eventful, Inc.</a></b></label>
			<div style="line-height:18px;">Eventful&reg; API</div>
		</div>
		<div class="frmOpt">
			<label><b><a href="http://code.google.com/apis/maps/" class="main" target="_blank">Google</a></b></label>
			<div style="line-height:15px;">Maps &amp; Geocoding APIs</div>
		</div>
		<div class="frmOpt">
			<label><b><a href="http://twitter.com/help/api" class="main" target="_blank">Twitter</a></b></label>
			<div style="line-height:15px;">Twitter API</div>
		</div>
		</div>
		<div style="width:53%;float:left;">
		<div class="frmOpt">
			<label class="about"><b><a href="http://tinymce.moxiecode.com/" class="main" target="_blank">Moxiecode</a></b></label>
               <div style="line-height:15px;">TinyMCE, MCImageManger</div>
		</div>
		<div class="frmOpt">
			<label class="about"><b><a href="http://phpmailer.worxware.com/" class="main" target="_blank">Worx International Inc.</a></b></label>
			<div style="line-height:15px;">PHPMailer</div>
		</div>
		<div class="frmOpt">
			<label class="about"><b><a href="http://www.janrain.com/openid-enabled" class="main" target="_blank">JanRain, Inc.</a></b></label>
			<div style="line-height:15px;">JanRain OpenID Library</div>
		</div>
		<div class="frmOpt">
			<label class="about"><b><a href="http://www.javascripttoolbox.com/lib/calendar/" class="main" target="_blank">Matt Kruse</a></b></label>
			<div style="line-height:15px;">JavaScript Date Picker</div>
		</div>
		<div class="frmOpt">
			<label class="about"><b>Walter Zorn</b></label>
			<div style="line-height:15px;">wz_tooltip</div>
		</div>
		<div class="frmOpt">
			<label class="about"><b><a href="http://www.famfamfam.com/" class="main" target="_blank">Mark James</a></b></label>
			<div style="line-height:15px;">Silk Icon Collection</div>
		</div>
		</div>
	</fieldset>
	<br /><br />
	<fieldset>
		<legend>Helios Calendar Software License Agreement</legend>
		<iframe src="<?php echo CalRoot;?>/docs/license.html" width="100%" height="300" frameborder="0"></iframe>
	</fieldset>