<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	session_start();
	include('includes/globals.php');
	
	$pathparts = pathinfo($_SERVER['SCRIPT_FILENAME']);
	$langDir = $pathparts['dirname'] . "/includes/lang/";
	
	if(isset($_GET['l'])){
		$dir = dir(realpath($langDir));
		if(is_dir($dir->path.'/'.$_GET['l'])){
			$_SESSION['LangSet'] = $_GET['l'];
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit();
		}//end if
	}//end if
	header('Location: ' . CalRoot . '/');?>