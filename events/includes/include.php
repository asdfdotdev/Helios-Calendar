<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	session_start();
	
	if(!isset($_SESSION['hc_favCat']) && isset($_COOKIE['hc_favCat'])){
		$_SESSION['hc_favCat'] = $_COOKIE['hc_favCat'];
	}//end if
	
	if(!isset($_SESSION['hc_favCity']) && isset($_COOKIE['hc_favCity'])){
		$_SESSION['hc_favCity'] = $_COOKIE['hc_favCity'];
	}//end if
		
	$incPrefix = "";
	if(isset($isAction)){$incPrefix = "../";}//end if
	include($incPrefix . 'includes/globals.php');
	include($incPrefix . 'includes/code.php');
	include($incPrefix . 'includes/connection.php');
?>