<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
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
							TemplateSource = '" . cIn(cleanQuotes($source),0) . "'
						WHERE PkID = " . cIn($nID));
						
			header("Location: " . CalAdminRoot . "/index.php?com=newsletteredit&msg=2");
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "newsletters(TemplateName, TemplateSource, IsActive)
						Values(	'" . cIn($name) . "',
								'" . cIn(cleanQuotes($source),0) . "', 1)");
			
			header("Location: " . CalAdminRoot . "/index.php?com=newsletteredit&msg=3");
		}//end if
		
	} else {
		doQuery("UPDATE " . HC_TblPrefix . "newsletters SET IsActive = 0 WHERE PkiD = " . cIn($_GET['dID']));
		
		header("Location: " . CalAdminRoot . "/index.php?com=newsletteredit&msg=1");
	}//end if	?>