<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
	
	
	NOTE: 	If Your Public Helios Calendar is not stored the events directory changes will need to be made
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
	
	define("HC_TblPrefix", "hc_");
	
	
	//	Helios Location Globals
	$rootURL = "";	// The root of your Helios Calendar location. Ex) http://www.helioscalendar.com
	define("CalRoot", "$rootURL/events");
	define("CalAdminRoot", "$rootURL/admin");
	define("MobileRoot", "$rootURL/events/wml");
	
	
	//	Helios State Settings (Change these only if you use an optional replacement State List)
	define("HC_StateLabel", "State");
	define("HC_StateInclude", "selectStates.php");
	
	
	/*	Helios Name and	Contact Globals
		CalName - Used to identify the website. e.g.: "all you need to access (CalName) event information" (Helios RSS Page)
		AdminName - Used to identify the Helios Administration Console
		CalAdmin & CalAdminEmail - Name & Email of the identity Helios sends emails as.	*/
		
	define("CalName", "");
	define("AdminName", "Helios Calendar Admin");
	define("CalAdmin", "");
	define("CalAdminEmail", "");
?>