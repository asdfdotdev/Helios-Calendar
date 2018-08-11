<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt();
	
	if(!isset($_GET['clear'])){
		$catIDs = "0";
		if(isset($_POST['catID'])){
			$catID = $_POST['catID'];
			foreach ($catID as $val){
				$catIDs = $catIDs . "," . $val;
			}//end for
		} else {
			unset($_SESSION["hc_favCat"]);
		}//end if
		
		$cityNames = "'_blank_'";
		if(isset($_POST['cityName'])){
			$cityName = $_POST['cityName'];
			foreach ($cityName as $val){
				$cityNames = $cityNames . ",'" . $val . "'";
			}//end for
		} else {
			unset($_SESSION["hc_favCity"]);
		}//end if
		
		if($catIDs != '0'){
			$_SESSION['hc_favCat'] = $catIDs;
		}//end if
		
		if($cityNames != "'_blank_'"){
			$_SESSION['hc_favCity'] = $cityNames;
		}//end if
		
		if(isset($_POST['cookieme'])){
			setcookie('hc_favCat', $catIDs, time()+604800, '/', false, 0);
			setcookie('hc_favCity', $cityNames, time()+604800, '/', false, 0);
		} else {
			setcookie('hc_favCat', "", time()-1, '/', false, 0);
			setcookie('hc_favCity', "", time()-1, '/', false, 0);
		}//end if
		
		header("Location: " . CalRoot . "/index.php?com=filter");
	} else {
		setcookie('hc_favCat', "", time()-1, '/', false, 0);
		setcookie('hc_favCity', "", time()-1, '/', false, 0);
		unset($_SESSION["hc_favCat"]);
		unset($_SESSION["hc_favCity"]);
		
		header("Location: " . CalRoot . "/");
	}//end if
?>