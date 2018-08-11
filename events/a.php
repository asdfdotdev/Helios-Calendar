<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('includes/include.php');
	
	hookDB();
	
	if(isset($_GET['a'])){
		$GUID = $_GET['a'];
	} else {
		header('Location: ' . CalRoot . '/');
	}//end if
	
	$query = "SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . $GUID . "'";
	$result = mysql_query($query) or die(mysql_error());
	
	if(hasRows($result)){
		if(mysql_result($result,0,6) == 0){
			doQuery("UPDATE " . HC_TblPrefix . "users SET IsRegistered = 1 WHERE PkID = '" . mysql_result($result,0,0) . "'");
			header('Location: ' . CalRoot . "/index.php?com=signup&msg=3");
		} else {
			header('Location: ' . CalRoot . "/");
		}//end if
	} else {
		header('Location: ' . CalRoot . '/');
	}//end if
?>
