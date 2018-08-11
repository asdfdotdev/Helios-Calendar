<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	session_start();
	include('../includes/globals.php');
	include('../cache/config.php');
	
	if(isset($_GET['l'])){
		$dir = dir(realpath("../includes/lang/"));
		if(is_dir($dir->path.'/'.$_GET['l'])){
			$_SESSION[$hc_cfg00 . 'LangSet'] = $_GET['l'];
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit();
		}//end if
	}//end if
	header('Location: ' . MobileRoot . '/');?>