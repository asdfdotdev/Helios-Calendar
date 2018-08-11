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
	
	$hc_langPath = "../events/includes/lang/";
	
	$incPrefix = "";
	if(isset($isAction)){$incPrefix = "../";}//end if
	include($incPrefix . '../events/includes/globals.php');
	include($incPrefix . '../events/includes/code.php');
	include($incPrefix . '../events/includes/connection.php');
?>