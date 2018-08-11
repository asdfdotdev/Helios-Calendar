<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	session_start();
	
	if( isset($_COOKIE["hc_favorites"]) ){
		$_SESSION['hc_favorites'] = $_COOKIE["hc_favorites"];
	}//end if
	
	define("HC_Version", "1.1.2");
	
	include('globals.php');
	include('code.php');
?>