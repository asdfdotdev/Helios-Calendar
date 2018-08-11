<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');
	
	$tID = (isset($_GET['tID']) && is_numeric($_GET['tID'])) ? $_GET['tID'] : 0;
	$oID = (isset($_GET['oID']) && is_numeric($_GET['oID'])) ? $_GET['oID'] : 0;
	$lID = (isset($_GET['lID']) && is_numeric($_GET['lID'])) ? $_GET['lID'] : 0;
	
	switch($tID){
		case 1:
			doQuery("UPDATE " . HC_TblPrefix . "events SET URLClicks = URLClicks + 1 WHERE PkID = '" . cIn($oID) . "'");
			$result = doQuery("SELECT ContactURL FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($oID));
			if(hasRows($result)){
				header('Location: ' . mysql_result($result,0,0));
			} else {
				die("Invalid Link");
			}//end if
			break;
		case 2:
			doQuery("UPDATE " . HC_TblPrefix . "events SET Directions = Directions + 1 WHERE PkID = '" . cIn($oID) . "'");
		case 3:
			$link = ($tID == 2) ? $hc_cfg8 : $hc_cfg9;
			$result = ($lID > 0) ?
				doQUERY("SELECT Address, City, State, Zip, Country, Lat, Lon FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($lID) . "'"):
				doQuery("SELECT LocationAddress, LocationCity, LocationState, LocationZip, LocCountry, NULL as Lat, NULL as Lon FROM " . HC_TblPrefix . "events WHERE PkID = '" . cIn($oID) . "'");
			
			if(hasRows($result)){
				$link = str_replace('[address]', rawurlencode(mysql_result($result,0,0)), $link);
				$link = str_replace('[city]', rawurlencode(mysql_result($result,0,1)), $link);
				$link = str_replace('[region]', rawurlencode(mysql_result($result,0,2)), $link);
				$link = str_replace('[postalcode]', rawurlencode(mysql_result($result,0,3)), $link);
				$link = str_replace('[country]', rawurlencode(mysql_result($result,0,4)), $link);
                    $link = str_replace('[lat]', rawurlencode(mysql_result($result,0,5)), $link);
                    $link = str_replace('[lon]', rawurlencode(mysql_result($result,0,6)), $link);
				
                    header('Location: ' . $link);
			} else {
				header('Location: ' . CalRoot);
			}//end if
			break;
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