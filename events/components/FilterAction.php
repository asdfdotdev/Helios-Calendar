<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	checkIt();
	
	if(!isset($_GET['clear'])){
		$catIDs = "0";
		
		if(isset($_POST['catID'])){
			$catID = $_POST['catID'];
				foreach ($catID as $val){
					$catIDs = $catIDs . "," . $val;
				}//end while
		}//end if
		
		$_SESSION['hc_favorites'] = $catIDs;
		
		if(isset($_POST['cookieme'])){
			setcookie('hc_favorites', $catIDs, time()+604800, '/', false, 0);
		} else {
			setcookie('hc_favorites', "", time()-1, '/', false, 0);
		}//end if
	
		header("Location: " . CalRoot . "/index.php?com=filter");
	} else {
		setcookie('hc_favorites', "", time()-1, '/', false, 0);
		$_SESSION['hc_favorites'] = '';
		
		header("Location: " . CalRoot . "/");
	}//end if
?>