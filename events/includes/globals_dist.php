<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
	
	NOTE:	If Your Public Helios Calendar is not stored the events directory changes will need to be made
			to some include statements in the Helios admin.
*/
	/*	Database Server Globals
		DATABASE_HOST - Database Host Name. Typically localhost
		DATABASE_NAME - The name of your Helios database
		DATABASE_USER - Username for your Helios database
		DATABASE_PASS - Password for your Helios database user	
		HC_TblPrefix - Prefix of your Helios datatables. */
		
	define("DATABASE_HOST", "");
	define("DATABASE_NAME", "");
	define("DATABASE_USER", "");
	define("DATABASE_PASS", "");
	
	define("HC_TblPrefix", "hc_");	// Optional
	
	
	//	Helios Location Globals
	$rootURL = "";	// The root of your Helios Calendar location. Ex) http://www.helioscalendar.com
	define("CalRoot", "$rootURL/events");
	define("CalAdminRoot", "$rootURL/admin");
	define("MobileRoot", "$rootURL/events/m/");
	
	
	/*	Helios Name and	Contact Globals
		CalName - Used to identify the website. e.g.: "all you need to access (CalName) event information" (Helios RSS Page)
		AdminName - Used to identify the Helios Administration Console
		CalAdmin & CalAdminEmail - Name & Email of the identity Helios sends emails as.	*/
		
	define("CalName", "");
	define("AdminName", "Helios Calendar Admin");
	define("CalAdmin", "");
	define("CalAdminEmail", "");
?>