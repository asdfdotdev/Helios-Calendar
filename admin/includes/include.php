<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$hc_langPath = "../events/includes/lang/";
	
	$incPrefix = (isset($isAction)) ? '../' : '';
	include($incPrefix . '../events/includes/globals.php');
	include($incPrefix . '../events/includes/code.php');

	session_start();

	$dbconnection = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
	mysql_select_db(DATABASE_NAME,$dbconnection);	
	
	if(!file_exists(realpath($incPrefix . '../events/cache/config.php'))){
		rebuildCache(0, 1);
	}//end if
	include($incPrefix . '../events/cache/config.php');
	
	if(!isset($_SESSION[$hc_cfg00 . 'LangSet'])){
		$_SESSION[$hc_cfg00 . 'LangSet'] = $hc_cfg28;
	}//end if
	
	if(!isset($_SESSION[$hc_cfg00 . 'hc_whoami'])){
		$_SESSION[$hc_cfg00 . 'hc_whoami'] = md5($_SERVER['REMOTE_ADDR'] . session_id());
	} elseif(md5($_SERVER['REMOTE_ADDR'] . session_id()) != $_SESSION[$hc_cfg00 . 'hc_whoami']){
		killAdminSession();
	}//end if
	
	if(isset($_SESSION[$hc_cfg00 . 'hc_SessionReset']) && $_SESSION[$hc_cfg00 . 'hc_SessionReset'] < date("U")){
		startNewSession();
	}//end if?>