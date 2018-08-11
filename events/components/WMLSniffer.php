<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	define('CACHE_FILE', 'includes/wurfl/data/cache.php');
	define('WURFL_USE_CACHE', 'LOG_ERROR');
	define('WURFL_LOG_FILE', 'includes/wurfl/data/wurfl.log');
	define('WURFL_FILE', 'includes/wurfl/data/wurfl.xml');
	define('WURFL_CLASS_FILE', 'includes/wurfl/wurfl_class.php');
	define('WURFL_PARSER_FILE', 'includes/wurfl/wurfl_parser.php');
	define('WURFL_PATCH_FILE', 'includes/wurfl/data/mypatch.xml');
	define('WURFL_CONFIG', 'includes/wurfl/data/wurfl_config.php');
	define('WURFL_AUTOLOAD', 'false');
	
	require_once('includes/wurfl/wurfl_class.php');
	
	$myDevice = new wurfl_class($wurfl, $wurfl_agents);
	$myDevice->GetDeviceCapabilitiesFromAgent($_SERVER["HTTP_USER_AGENT"]);
	if ($myDevice->browser_is_wap ) {
	
	header("Content-type: text/vnd.wap.wml");
	echo("<?xml version=\"1.0\"?>\n");
	echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\""
		." \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n");
	?>

	<wml>
		<card title="redirect">
		<onevent type="ontimer">
			<go href="wml/"/>
		</onevent><timer value="1"/>
		</card>
	</wml>
	<?
	exit;
	}//end if
?>