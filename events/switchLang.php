<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	session_start();
	include('includes/globals.php');
	include('cache/config.php');
	
	$pathparts = pathinfo($_SERVER['SCRIPT_FILENAME']);
	$langDir = $pathparts['dirname'] . "/includes/lang/";
	
	if(isset($_GET['l'])){
		$dir = dir(realpath($langDir));
		if(is_dir($dir->path.'/'.$_GET['l'])){
			$_SESSION[$hc_cfg00 . 'LangSet'] = $_GET['l'];
			if(isset($_SERVER['HTTP_REFERER'])){
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			} else {
				header('Location: ' . CalRoot . '/');
			}//end if
			exit();
		}//end if
	}//end if
	header('Location: ' . CalRoot . '/');?>