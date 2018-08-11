<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$msgID = 1;
	$delIDs = array();
	$apiFail = false;
	$uID = (isset($_GET['uID']) && is_numeric($_GET['uID'])) ? cIn(strip_tags($_GET['uID'])) : 0;
	
	if(isset($_GET['series'])){
		$result = doQuery("SELECT GROUP_CONCAT(PkID) FROM " . HC_TblPrefix . "events WHERE SeriesID = '".cIn(strip_tags($_GET['series']))."'");
		if(hasRows($result))
			$delIDs = explode(',',mysql_result($result,0,0));
	} elseif(isset($_POST['eventID'])) {
		$delIDs = (isset($_POST['eventID'])) ? $_POST['eventID'] : array();
	} elseif(isset($_GET['dID'])){
		$delIDs[] = $_GET['dID'];
	}
	
	$delIDs = cIn(implode(',',array_filter($delIDs,'is_numeric')));
	
	if($delIDs != ''){
		doQuery("UPDATE " . HC_TblPrefix . "events SET IsActive = 0 WHERE PkID IN(" . $delIDs . ")");
		clearCache();

		$resultD = doQuery("SELECT NetworkID, NetworkType FROM " . HC_TblPrefix . "eventnetwork WHERE EventID IN (" . $delIDs . ") ORDER BY NetworkType");
		if(hasRows($resultD)){
			while($row = mysql_fetch_row($resultD)){
				$netID = $row[0];
				switch($row[1]){
					case 1:
						include(HCPATH.HCINC.'/api/eventful/EventDelete.php');
						break;
					case 2:
						include(HCPATH.HCINC.'/api/eventbrite/EventDelete.php');
						break;
					case 5:
						include(HCPATH.HCINC.'/api/facebook/EventDelete.php');
						break;
				}
			}
		}
	}
		
	if($apiFail != false)
		exit();
	
	if(isset($_GET['oID']) OR isset($_POST['oID']))
		header("Location: " . AdminRoot . "/index.php?com=eventorphan&msg=1");
	elseif(isset($_POST['pID']) || isset($_GET['pID']))
		header("Location: " . AdminRoot . "/index.php?com=eventpending&msg=5");
	elseif(isset($_GET['dpID']) || isset($_POST['dpID']))
		header("Location: " . AdminRoot . "/index.php?com=reportdup&msg=1");
	elseif(isset($_GET['rpID']))
		header("Location: " . AdminRoot . "/index.php?com=reportdup&msg=1");
	elseif(isset($_GET['uID']))
		header("Location: " . AdminRoot . "/index.php?com=useredit&uID=".$uID."&msg=1");
	else
		header("Location: " . AdminRoot . "/index.php?com=eventsearch&sID=2&msg=" . $msgID);
?>