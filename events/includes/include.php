<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	session_start();
	
	if(!isset($_SESSION['hc_favCat']) && isset($_COOKIE['hc_favCat'])){
		$_SESSION['hc_favCat'] = base64_decode($_COOKIE['hc_favCat']);
	}//end if
	
	if(!isset($_SESSION['hc_favCity']) && isset($_COOKIE['hc_favCity'])){
		$_SESSION['hc_favCity'] = base64_decode($_COOKIE['hc_favCity']);
	}//end if
	
	$hc_langPath = "includes/lang/";
	
	$incPrefix = "";
	if(isset($isAction)){$incPrefix = "../";}//end if
	include($incPrefix . 'includes/globals.php');
	include($incPrefix . 'includes/code.php');
	include($incPrefix . 'includes/connection.php');
?>