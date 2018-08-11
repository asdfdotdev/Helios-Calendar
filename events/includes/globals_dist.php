<?php
/**
 * This file is part of Helios Calendar it's use is governed by the Helios Calendar Software License Agreement.
 *
 * Note: If Your Public Helios Calendar is not stored in the /events directory changes will need to be made
 * to some include statements in the Helios Calendar admin. It is NOT recommended to rename the events directory.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	/**
	 * MySQL Hostname. Typically localhost, however, it may vary for your hosting environment.
	 */
	define("DATABASE_HOST", "");
	/**
	 * MySQL Database Name
	 */
	define("DATABASE_NAME", "");
	/**
	 * MySQL Server Username.
	 */
	define("DATABASE_USER", "");
	/**
	 * MySQL Server Password.
	 */
	define("DATABASE_PASS", "");
	/**
	 * MySQL Datatable Prefix. Should only be edited prior to installation.
	 */
	define("HC_TblPrefix", "hc_");

	/**
	 * Used to construct the URL globals that follow. Edit this to your Helios Calendar install path, do not edit the individual globals.
	 */
	$rootURL = "";
	/**
	 * Public Calendar URL.
	 */
	define("CalRoot", "$rootURL/events");
	/**
	 * Admin Console URL.
	 */
	define("CalAdminRoot", "$rootURL/admin");
	/**
	 * Mobile Site URL.
	 */
	define("MobileRoot", "$rootURL/events/m");

	/**
	 * The name of your calendar.
	 */
	define("CalName", "");
	/**
	 * A random string used to create cookies named uniquely for your calendar.
	 * Should be at least 40 random characters. Generator available at link.
	 * @link http://www.refreshmy.com/rando/
	 */
	define("HC_Rando", "");
?>