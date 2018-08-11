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
	include('cache/config.php');
	session_name($hc_cfg00p);
	session_start();
	include('includes/globals.php');

	$pathparts = pathinfo($_SERVER['SCRIPT_FILENAME']);
	$langDir = $pathparts['dirname'] . "/includes/lang/";
	
	if(isset($_GET['l'])){
		$dir = dir(realpath($langDir));
		if(is_dir($dir->path.'/'.$_GET['l'])){
			$_SESSION['LangSet'] = $_GET['l'];
			
			if(isset($_SERVER['HTTP_REFERER'])){
				header('Location: ' . $_SERVER['HTTP_REFERER']);
				exit();
			}//end if
		}//end if
	}//end if
	
	header('Location: ' . CalRoot . '/');?>