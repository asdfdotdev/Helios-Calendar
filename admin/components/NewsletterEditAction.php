<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	
	hookDB();
	
	if(!isset($_GET['dID'])){
		$nID = $_POST['nID'];
		$name = $_POST['tempname'];
		$source = $_POST['tempsource'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE PkID = " . cIn($nID));
		if(hasRows($result)){
			//update
			doQuery("UPDATE " . HC_TblPrefix . "newsletters
						SET TemplateName = '" . cIn($name) . "',
							TemplateSource = '" . cIn($source) . "'
						WHERE PkID = " . cIn($nID));
						
			header("Location: " . CalAdminRoot . "/index.php?com=newsletteredit&nID=" . $nID . "&msg=1");
		} else {
			//insert
			doQuery("INSERT INTO " . HC_TblPrefix . "newsletters(TemplateName, TemplateSource, IsActive)
						Values(	'" . cIn($name) . "',
								'" . cIn($source) . "', 1)");
			
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "newsletters");
			$nID = mysql_result($result,0,0);
			
			header("Location: " . CalAdminRoot . "/index.php?com=newsletteredit&nID=" . $nID . "&msg=2");
		}//end if
		
	} else {
		doQuery("UPDATE " . HC_TblPrefix . "newsletters SET IsActive = 0 WHERE PkiD = " . cIn($_GET['dID']));
		
		header("Location: " . CalAdminRoot . "/index.php?com=newsletteredit&msg=1");
	}//end if
?>

