<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	
	if(!isset($_GET['clear']) && isset($_POST['catID']) || isset($_POST['cityName'])){
		$catIDs = "0";
		if(isset($_POST['catID'])){
			$catID = $_POST['catID'];
			foreach ($catID as $val){
				$catIDs = $catIDs . "," . cleanXMLChars(strip_tags(cIn($val)));
			}//end for
		} else {
			unset($_SESSION[$hc_cfg00 . 'hc_favCat']);
		}//end if
		
		$cityNames = "";
		if(isset($_POST['cityName'])){
			$cityName = $_POST['cityName'];
			$cityNames = "";
			foreach ($cityName as $val){
				if($cityNames != ''){$cityNames .= ",";}
				$cityNames .= "'" . cleanXMLChars(strip_tags(cIn($val))) . "'";
			}//end for
		} else {
			unset($_SESSION[$hc_cfg00 . 'hc_favCity']);
		}//end if
		
		if($catIDs != '0'){
			$_SESSION[$hc_cfg00 . 'hc_favCat'] = $catIDs;
		}//end if
		
		if($cityNames != ""){
			$_SESSION[$hc_cfg00 . 'hc_favCity'] = $cityNames;
		}//end if
		
		if(isset($_POST['cookieme'])){
			if($catIDs != "0"){
				setcookie('hc_favCat', base64_encode($catIDs), time()+604800, '/', false, 0);
			}//end if
			if($cityNames != ''){
				setcookie('hc_favCity', base64_encode($cityNames), time()+604800, '/', false, 0);
			}//end if
		} else {
			setcookie('hc_favCat', "", time()-86400, '/', false, 0);
			setcookie('hc_favCity', "", time()-86400, '/', false, 0);
		}//end if
		
		$msgID = 1;
		
	} else {
		setcookie('hc_favCat', "", time()-86400, '/', false, 0);
		setcookie('hc_favCity', "", time()-86400, '/', false, 0);
		unset($_SESSION[$hc_cfg00 . 'hc_favCat']);
		unset($_SESSION[$hc_cfg00 . 'hc_favCity']);
		
		$msgID = 2;
	}//end if
	
	header("Location: " . CalRoot . "/index.php?com=filter&msg=" . $msgID);?>