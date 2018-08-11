<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	error_reporting(0);
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	echo '<div id="logo_about"><img src="'.AdminRoot.'/img/logoAbout.png" width="260" height="59" alt="" /></div>';
	
	$ip = gethostbyname("www.refreshmy.com");
	if($fp = fsockopen($ip, 80, $errno, $errstr, 1)){
		$read = '';
		$request = "GET /_update/h/cur.php HTTP/1.1\r\nHost: www.refreshmy.com\r\nConnection: Close\r\n\r\n";
		fwrite($fp, $request);
		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}
		fclose($fp);

		$output = explode("/bof", $read);
		if(isset($output[1]))
			$output = explode("/eof", $output[1]);
		
		if($output[0] != '' && $output[0] > $hc_cfg[49]){
			$hc_Side[] = array('https://www.refreshmy.com/members/','info.png','Download Version <b>' . $output[0] . '</b>',1);
			feedback(1,'A new version of Helios Calendar is available.');
		}
	}
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(16,17,18,19) ORDER BY PkID");
	$resultV = doQuery("SELECT VERSION()");
	$load = array();
	if(function_exists('exec')){
		$load = strtolower(exec("uptime"));
		$load = explode('load average: ',$load);
	}
	$ssl_support = (!function_exists('openssl_open')) ? 'Unavailable' : OPENSSL_VERSION_TEXT;
	
	echo '
		<fieldset class="about">
			<legend>About Helios Calendar</legend>
			<label><b>Developer:</b></label>
			<span><a href="http://www.refreshmy.com" target="_blank">Refresh Web Development LLC</a></span>
			<label><b>Copyright:</b></label>
			<span>Copyright &copy; 2004-'.date("Y").', All Rights Reserved</span>
			<label><b>Website:</b></label>
			<span><a href="http://www.helioscalendar.com" target="_blank">www.HeliosCalendar.com</a></span>
		</fieldset>
		<fieldset class="about">
			<legend>About This Install</legend>
			<label><b>Version:</b></label>
			<span>Helios Calendar '.$hc_cfg[49].'</span>
			<label><b>Licensed To:</b></label>
			<span>'.cOut(mysql_result($result,0,0)).' ('.cOut(mysql_result($result,2,0)).')</span>
			<label><b>Licensed For:</b></label>
			<span>'.cOut(mysql_result($result,1,0)).'</span>
			<label><b>License Code:</b></label>
			<span>'.cOut(mysql_result($result,3,0)).'</span>
			<label><b>PHP Version:</b></label>
			<span>'.phpversion().' (<a href="'.AdminRoot.'/components/AboutPHP.php" target="_blank">About PHP</a>)</span>
			<label><b>MySQL Version:</b></label>
			<span>'.mysql_result($resultV,0,0).'</span>
			'.((isset($load[1]) && $load[1] != '') ? '<label><b>Load Average:</b></label>
			<span>'.$load[1].'</span>':'').'
			<label><b>SSL Support:</b></label>
			<span>'.$ssl_support.'</span>
		</fieldset>
		<fieldset class="about">
			<legend>Credit &amp; Thanks</legend>
			<p>Refresh Web Development would like to thank the following businesses, organizations &amp; individuals for developing awesome components, libraries &amp; services and making them available for use in Helios Calendar.</p>
			<ul style="list-style:none;line-height:25px;">
				<li>
					<b>APIs:</b>
					<a href="http://dev.bitly.com/" target="_blank">bitly</a>,
					<a href="http://developer.eventbrite.com/" target="_blank">Eventbrite</a>,
					<a href="http://api.eventful.com/" target="_blank">Eventful&reg;</a>,
					<a href="http://developers.facebook.com/" target="_blank">Facebook</a>,
					<a href="https://developers.google.com/" target="_blank">Google</a>,
					<a href="https://dev.twitter.com/" target="_blank">Twitter</a>
				</li>
				<li>
					<b>Comments:</b>
					<a href="http://www.disqus.com/" target="_blank">Disqus</a>,
					<a href="http://www.livefyre.com/" target="_blank">Livefyre</a>
				</li>
				<li>
					<b>Icons:</b>
					<a href="http://www.famfamfam.com/" target="_blank">Silk Icon Collection</a>
				</li>
				<li>
					<b>Libraries:</b>
					<a href="https://github.com/facebook/facebook-php-sdk" target="_blank">Facebook PHP SDK</a>,
					<a href="http://www.javascripttoolbox.com/lib/calendar/" target="_blank">Matt Kruse</a>,
					<a href="http://www.openlayers.org/" target="_blank">OpenLayers</a>,
					<a href="http://code.google.com/a/apache-extras.org/p/phpmailer/" target="_blank">PHPMailer Project</a>,
					<a href="http://www.tinymce.com/" target="_blank">TinyMCE</a> (<a href="http://www.tinymce.com/enterprise/mcimagemanager.php" target="_blank">MC_ImageManager</a>)
				</li>
				<li>
					<b>Maps:</b>
					<a href="http://www.bingmapsportal.com/" target="_blank">Bing Maps</a>,
					<a href="https://developers.google.com/maps/" target="_blank">Google Maps</a>,
					<a href="http://developer.mapquest.com/web/products/open/map" target="_blank">MapQuest</a>,
					<a href="http://developer.yahoo.com" target="_blank">Yahoo Maps</a>
				</li>
			</ul>
		</fieldset>
		<fieldset>
			<legend>Helios Calendar Software License Agreement</legend>
			<iframe src="'.AdminRoot.'/license.html" width="100%" height="300" frameborder="0"></iframe>
		</fieldset>';
?>