<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	
	$tID = (isset($_GET['tID']) && is_numeric($_GET['tID'])) ? $_GET['tID'] : 0;
	$oID = (isset($_GET['oID']) && is_numeric($_GET['oID'])) ? $_GET['oID'] : 0;
	$lID = (isset($_GET['lID']) && is_numeric($_GET['lID'])) ? $_GET['lID'] : 0;
	
	switch($tID){
	//	Event URL Clicked
		case 1:
			doQuery("UPDATE " . HC_TblPrefix . "events SET URLClicks = URLClicks + 1 WHERE PkID = '" . cIn($oID) . "'");
			$result = doQuery("SELECT ContactURL FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($oID));
			if(hasRows($result)){
				header('Location: ' . mysql_result($result,0,0));
			} else {
				die("Invalid Link");
			}//end if
			break;
	//	Weather and Map Link	
		case 2:
			doQuery("UPDATE " . HC_TblPrefix . "events SET Directions = Directions + 1 WHERE PkID = '" . cIn($oID) . "'");
		case 3:
			$link = ($tID == 2) ? $hc_cfg8 : $hc_cfg9;
			$result = ($lID > 0) ?
				doQUERY("SELECT Address, City, State, Zip, Country FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($lID) . "'"):
				doQuery("SELECT LocationAddress, LocationCity, LocationState, LocationZip, LocCountry FROM " . HC_TblPrefix . "events WHERE PkID = '" . cIn($oID) . "'");
			
			if(hasRows($result)){
				$link = str_replace('[address]', rawurlencode(mysql_result($result,0,0)), $link);
				$link = str_replace('[city]', rawurlencode(mysql_result($result,0,1)), $link);
				$link = str_replace('[region]', rawurlencode(mysql_result($result,0,2)), $link);
				$link = str_replace('[postalcode]', rawurlencode(mysql_result($result,0,3)), $link);
				$link = str_replace('[country]', rawurlencode(mysql_result($result,0,4)), $link);
				
				header('Location: ' . $link);
			} else {
				header('Location: ' . CalRoot);
			}//end if
			break;
	//	Location URL Link	
		case 4:
			$result = doQuery("SELECT URL FROM " . HC_TblPrefix . "locations WHERE PkID = " . cIn($oID));
			if(hasRows($result)){
				doQuery("UPDATE " . HC_TblPrefix . "locations SET URLClicks = URLClicks + 1 WHERE PkID = '" . cIn($oID) . "'");
				header('Location: ' . mysql_result($result,0,0));
			} else {
				die("Invalid Link");
			}//end if
			break;
		default:
			header('Location: ' . CalRoot);
			break;
	}//end switch?>