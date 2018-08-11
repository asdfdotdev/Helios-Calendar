<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	if(!isset($_GET['dID'])){
		$nID = (isset($_POST['nID']) && is_numeric($_POST['nID'])) ? cIn(strip_tags($_POST['nID'])) : 0;
		$name = isset($_POST['tempname']) ? cIn($_POST['tempname']) : '';
		$source = isset($_POST['tempsource']) ? cIn(cleanQuotes($_POST['tempsource'],0),0) : '';
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templatesnews WHERE PkID = '" . $nID . "'");
		if(hasRows($result)){
			doQuery("UPDATE " . HC_TblPrefix . "templatesnews
						SET TemplateName = '" . $name . "', TemplateSource = '" . $source . "'
						WHERE PkID = '" . $nID . "'");
						
			header("Location: " . AdminRoot . "/index.php?com=mailtmplt&msg=2");
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "templatesnews(TemplateName, TemplateSource, IsActive)
						Values('" . $name . "','" . $source . "', 1)");
			
			header("Location: " . AdminRoot . "/index.php?com=mailtmplt&msg=3");
		}	
	} else {
		doQuery("UPDATE " . HC_TblPrefix . "templatesnews SET IsActive = 0 WHERE PkiD = '" . cIn(strip_tags($_GET['dID'])) . "'");
		header("Location: " . AdminRoot . "/index.php?com=mailtmplt&msg=1");
	}
?>