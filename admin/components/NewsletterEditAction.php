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
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	if(!isset($_GET['dID'])){
		$nID = $_POST['nID'];
		$name = $_POST['tempname'];
		$source = $_POST['tempsource'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE PkID = " . cIn($nID));
		if(hasRows($result)){
			doQuery("UPDATE " . HC_TblPrefix . "newsletters
						SET TemplateName = '" . cIn($name) . "',
							TemplateSource = '" . cIn($source) . "'
						WHERE PkID = " . cIn($nID));
						
			header("Location: " . CalAdminRoot . "/index.php?com=newsletteredit&msg=2");
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "newsletters(TemplateName, TemplateSource, IsActive)
						Values(	'" . cIn($name) . "',
								'" . cIn($source) . "', 1)");
			
			header("Location: " . CalAdminRoot . "/index.php?com=newsletteredit&msg=3");
		}//end if
		
	} else {
		doQuery("UPDATE " . HC_TblPrefix . "newsletters SET IsActive = 0 WHERE PkiD = " . cIn($_GET['dID']));
		
		header("Location: " . CalAdminRoot . "/index.php?com=newsletteredit&msg=1");
	}//end if	?>