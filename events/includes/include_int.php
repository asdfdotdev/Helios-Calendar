<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	ini_set("include_path",ini_get('include_path').";".dirname($_SERVER['SCRIPT_FILENAME']));
	
	$hc_langPath = "lang/";
	
	include($incPrefix . 'includes/globals.php');
	include($incPrefix . 'includes/code.php');

	$dbconnection = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
	mysql_select_db(DATABASE_NAME,$dbconnection);
	
	include($incPrefix . 'cache/config.php');
	
	if(!isset($_SESSION[$hc_cfg00 . 'LangSet'])){
		$_SESSION[$hc_cfg00 . 'LangSet'] = $hc_cfg28;
	}//end if
	
	include($incPrefix . 'includes/lang/' . $hc_cfg28 . '/config.php');
	setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);