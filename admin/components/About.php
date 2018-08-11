<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	error_reporting(0);
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
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
			<label><b>Author:</b></label>
			<span><a href="https://github.com/chrislarrycarl" target="_blank">Chris Carlevato</a></span>
			<label><b>Copyleft:</b></label>
			<span><span style="-moz-transform: scaleX(-1); -o-transform: scaleX(-1); -webkit-transform: scaleX(-1); transform: scaleX(-1); display: inline-block;margin:0 5px 0 0;">&copy;</span> 2004-'.date("Y").'</span>
			<label><b>License:</b></label>
			<span><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GNU GPL</a></span>
		</fieldset>
		<fieldset class="about">
			<legend>About This Install</legend>
			<label><b>Version:</b></label>
			<span>Helios Calendar '.$hc_cfg[49].'</span>
			<label><b>API:</b></label>
			<span>'.$hc_cfg[133].'</span>
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
			<p>The authors would like to thank the following businesses, organizations &amp; individuals for developing awesome components, libraries &amp; services and making them available for use in Helios Calendar.</p>
			<ul style="list-style:none;line-height:25px;">
				<li>
					<b>APIs:</b>
					<a href="http://dev.bitly.com/" target="_blank">bitly</a>,
					<a href="http://developer.eventbrite.com/" target="_blank">Eventbrite</a>,
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
					<a href="https://github.com/PHPMailer/PHPMailer" target="_blank">PHPMailer Project</a>,
					<a href="http://www.tinymce.com/" target="_blank">TinyMCE</a>
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
			<legend>License</legend>
			<iframe src="'.CalRoot.'/LICENSE" width="100%" height="300" frameborder="0"></iframe>
		</fieldset>';
?>