<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	ini_set("include_path",ini_get('include_path').";".dirname($_SERVER['SCRIPT_FILENAME']));
	
	$hc_langPath = "lang/";
	include($incPrefix . 'includes/globals.php');
	include($incPrefix . 'includes/code.php');

	$dbconnection = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
	mysql_select_db(DATABASE_NAME,$dbconnection);
	
	include($incPrefix . 'cache/config.php');
	
	if(!isset($_SESSION['LangSet'])){
		$_SESSION['LangSet'] = $hc_cfg28;
	}//end if
	
	include($incPrefix . 'includes/lang/' . $hc_cfg28 . '/config.php');
	setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);