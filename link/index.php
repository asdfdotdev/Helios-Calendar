<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	
	action_headers();
	$tID = (isset($_GET['tID']) && is_numeric($_GET['tID'])) ? cIn(strip_tags($_GET['tID'])) : 0;
	$oID = (isset($_GET['oID']) && is_numeric($_GET['oID'])) ? cIn(strip_tags($_GET['oID'])) : 0;
	$lID = (isset($_GET['lID']) && is_numeric($_GET['lID'])) ? cIn(strip_tags($_GET['lID'])) : 0;
	
	switch($tID){
		case 1:
			if(!preg_match("$hc_cfg[85]i",$_SERVER['HTTP_USER_AGENT']))
				DoQuery("UPDATE " . HC_TblPrefix . "events SET URLClicks = URLClicks + 1 WHERE PkID = ?", array($oID));
			
			$result = DoQuery("SELECT ContactURL FROM " . HC_TblPrefix . "events WHERE PkID = ?", array($oID));
			if(hasRows($result))
				header('Location: ' . hc_mysql_result($result,0,0));
			else
				header('Location: ' . CalRoot);
			break;
		case 2:
			if(!preg_match("$hc_cfg[85]i",$_SERVER['HTTP_USER_AGENT']))
				DoQuery("UPDATE " . HC_TblPrefix . "events SET Directions = Directions + 1 WHERE PkID = ?", array($oID));
		case 3:
			$link = ($tID == 2) ? $hc_cfg[8] : $hc_cfg[9];
			$result = ($lID > 0) ?
				DoQuery("SELECT Address, City, State, Zip, Country, Lat, Lon FROM " . HC_TblPrefix . "locations WHERE PkID = ?", array($lID)):
				DoQuery("SELECT LocationAddress, LocationCity, LocationState, LocationZip, LocCountry, NULL as Lat, NULL as Lon FROM " . HC_TblPrefix . "events WHERE PkID = ?", array($oID));
			
			if(hasRows($result)){
				$link = str_replace('[address]', rawurlencode(hc_mysql_result($result,0,0)), $link);
				$link = str_replace('[city]', rawurlencode(hc_mysql_result($result,0,1)), $link);
				$link = str_replace('[region]', rawurlencode(hc_mysql_result($result,0,2)), $link);
				$link = str_replace('[postalcode]', rawurlencode(hc_mysql_result($result,0,3)), $link);
				$link = str_replace('[country]', rawurlencode(hc_mysql_result($result,0,4)), $link);
                    $link = str_replace('[lat]', rawurlencode(hc_mysql_result($result,0,5)), $link);
                    $link = str_replace('[lon]', rawurlencode(hc_mysql_result($result,0,6)), $link);
				
                    header('Location: ' . $link);
			} else {
				header('Location: ' . CalRoot);
			}
			break;
		case 4:
			$result = DoQuery("SELECT URL FROM " . HC_TblPrefix . "locations WHERE PkID = ?", array($oID));
			if(hasRows($result)){
				if(!preg_match("$hc_cfg[85]i",$_SERVER['HTTP_USER_AGENT']))
					DoQuery("UPDATE " . HC_TblPrefix . "locations SET URLClicks = URLClicks + 1 WHERE PkID = ?", array($oID));
				header('Location: ' . hc_mysql_result($result,0,0));
			} else {
				header('Location: ' . CalRoot);
			}
			break;
		default:
			header('Location: ' . CalRoot);
			break;
	}
?>