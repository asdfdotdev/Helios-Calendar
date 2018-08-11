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
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	if(!isset($_GET['dID'])){
		$nID = isset($_POST['nID']) ? cIn($_POST['nID']) : 0;
		$name = isset($_POST['tempname']) ? cIn($_POST['tempname']) : '';
		$source = isset($_POST['tempsource']) ? cIn(cleanQuotes($_POST['tempsource'],0),0) : '';
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templatesnews WHERE PkID = '" . cIn($nID) . "'");
		if(hasRows($result)){
			doQuery("UPDATE " . HC_TblPrefix . "templatesnews
						SET TemplateName = '" . $name . "', TemplateSource = '" . $source . "'
						WHERE PkID = '" . $nID . "'");
						
			header("Location: " . CalAdminRoot . "/index.php?com=mailtmplt&msg=2");
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "templatesnews(TemplateName, TemplateSource, IsActive)
						Values('" . $name . "','" . $source . "', 1)");
			
			header("Location: " . CalAdminRoot . "/index.php?com=mailtmplt&msg=3");
		}//end if
		
	} else {
		doQuery("UPDATE " . HC_TblPrefix . "templatesnews SET IsActive = 0 WHERE PkiD = '" . cIn($_GET['dID']) . "'");
		header("Location: " . CalAdminRoot . "/index.php?com=mailtmplt&msg=1");
	}//end if	?>