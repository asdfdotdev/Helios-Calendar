<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	if($_SESSION['Instructions'] == 1){
		$_SESSION['Instructions'] = 0;
	} else {
		$_SESSION['Instructions'] = 1;
	}//end if
	
	doQuery("UPDATE " . HC_TblPrefix . "admin SET ShowInstructions = " . $_SESSION['Instructions'] . " WHERE PkID = " . $_SESSION['AdminPkID']);
	
	header("Location: " . $_SERVER['HTTP_REFERER']);
?>