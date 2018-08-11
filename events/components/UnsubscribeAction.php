<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	hookDB();
	
	if(isset($_POST['dID'])){
		$guid = $_POST['dID'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . $guid . "'");
		$uID = mysql_result($result,0,0);
		if(hasRows($result)){
			
			doQuery("DELETE FROM " . HC_TblPrefix . "users WHERE PkID = " . $uID);
			doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = " . $uID);
			
			header('Location: ' . CalRoot . '/index.php?&msg=1');
			
		} else {
		
			header('Location: ' . CalRoot . '/asdf');
		
		}//end if
		
	} else {
		header('Location: ' . CalRoot . '/asdf2');
		
	}//end if
?>