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
	
	$deleteString = "0";
	if(isset($_POST['eventID'])){
		$eventID = $_POST['eventID'];
		$x = 0;
		while($x < count($eventID)){
			$deleteString = $deleteString . ", " . $eventID[$x];
			$x++;
		}//end while
	} else {
		$deleteString = $_GET['dID'];
	}//end if
	
	doQuery("UPDATE " . HC_TblPrefix . "events SET IsActive = 0 WHERE PkID IN(" . cIn($deleteString) . ")");
	
	if(!isset($_GET['oID'])){
		header("Location: " . CalAdminRoot . "/index.php?com=eventsearch&sID=2&msg=1");
	} else {
		header("Location: " . CalAdminRoot . "/index.php?com=eventorphan&msg=1");
	}//end if
?>