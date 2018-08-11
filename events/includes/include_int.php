<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
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