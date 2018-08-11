<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
	
	NOTE:	If Your Public Helios Calendar is not stored in the events directory changes will need to be made
			to some include statements in the Helios admin. It is NOT recomended to rename the events directory.
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
	
	define("HC_TblPrefix", "hc_");	// Optional, But Strongly Recomended.

	
	//	Helios Location Globals
	$rootURL = "";	// The root of your Helios Calendar location. Ex) http://www.helioscalendar.com
	define("CalRoot", "$rootURL/events");
	define("CalAdminRoot", "$rootURL/admin");
	define("MobileRoot", "$rootURL/events/m");
	
	
	/*	Helios Name and Contact Globals
		CalName - Used to identify your calendar.
		CalAdmin & CalAdminEmail - Name & Email of the identity Helios sends emails as.	*/
		
	define("CalName", "");
	define("CalAdmin", "");
	define("CalAdminEmail", "");
?>