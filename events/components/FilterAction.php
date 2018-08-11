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
	
	$catIDs = "0";
	
	if(isset($_POST['catID'])){
		$catID = $_POST['catID'];
			foreach ($catID as $val){
				$catIDs = $catIDs . "," . $val;
			}//end while
	}//end if
	
	$_SESSION['hc_favorites'] = $catIDs;
	
	header("Location: " . CalRoot . "/");
?>