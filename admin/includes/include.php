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
	$hc_langPath = "../events/includes/lang/";
	$incPrefix = (isset($isAction)) ? '../' : '';

	if(!file_exists($incPrefix . '../events/includes/globals.php')){
		exit();
	}//end if
	include($incPrefix . '../events/includes/globals.php');
	include($incPrefix . '../events/includes/code.php');

	$dbc = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
	mysql_select_db(DATABASE_NAME,$dbc);

	if(!file_exists(realpath($incPrefix . '../events/cache/config.php'))){
		rebuildCache(0, 1);
	}//end if
	include($incPrefix . '../events/cache/config.php');

	session_name($hc_cfg00);
	session_start();

	if(!isset($_SESSION['LangSet'])){
		$_SESSION['LangSet'] = $hc_cfg28;
	}//end if
	
	if(!isset($_SESSION['hc_whoami'])){
		$_SESSION['hc_whoami'] = md5($_SERVER['REMOTE_ADDR'] . session_id());
	} elseif(md5($_SERVER['REMOTE_ADDR'] . session_id()) != $_SESSION['hc_whoami']){
		killAdminSession();
	}//end if
	
	if(isset($_SESSION['hc_SessionReset']) && $_SESSION['hc_SessionReset'] < date("U")){
		startNewSession();
	}//end if?>