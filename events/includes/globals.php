<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
	
	
	NOTE: 	If Your Public Helios Calendar is not stored in the events directory changes will need to be made
			to some include statements in the Helios admin.
*/
	//	Database Server Globals
	define("DATABASE_HOST", "localhost");
	define("DATABASE_NAME", "helios");
	define("DATABASE_USER", "dev");
	define("DATABASE_PASS", "asdf");
	
	define("HC_TblPrefix", "hc_");	// Optional
	
	
	//	Helios Location Globals
	$rootURL = "http://lakota/helios";
	define("CalRoot", "$rootURL/events");
	define("CalAdminRoot", "$rootURL/admin");
	define("MobileRoot", "$rootURL/events/wml");
	
	
	/*	Helios Name and	Contact Globals
		CalName - Used to identify the website. e.g.: "all you need to access (CalName) event information" (Helios RSS Page)
		AdminName - Used to identify the Helios Administration Console
		CalAdmin & CalAdminEmail - Name & Email of the primary Helios Administrator. Used for emails sent by Helios.	*/
		
	define("CalName", "Helios Demo");
	define("AdminName", "Helios Admin");
	define("CalAdmin", "Chris Carlevato");
	define("CalAdminEmail", "helios@refreshwebdev.com");
?>