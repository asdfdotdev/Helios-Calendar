<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	$msgID = 1;
	
	$delIDs = (isset($_POST['eventID'])) ? cIn(implode(',', $_POST['eventID'])) : cIn($_GET['dID']);

	doQuery("UPDATE " . HC_TblPrefix . "events SET IsActive = 0 WHERE PkID IN(" . cIn($delIDs) . ")");
	doQuery("UPDATE " . HC_TblPrefix . "comments SET IsActive = 0 WHERE TypeID = 1 AND EntityID IN(" . cIn($delIDs) . ")");
	
	$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "comments WHERE TypeID = 1 AND EntityID IN(" . cIn($delIDs) . ")");
	while($row = mysql_fetch_row($result)){
		doQuery("UPDATE " . HC_TblPrefix . "commentsreportlog SET IsActive = 0 WHERE CommentID = '" . cIn($row[0]) . "'");
	}//end while
	
	$resultD = doQuery("SELECT NetworkID, NetworkType FROM " . HC_TblPrefix . "eventnetwork WHERE EventID IN (" . $delIDs . ") ORDER BY NetworkType");
	while($row = mysql_fetch_row($resultD)){
		$netID = $row[0];
		if($row[1] == 1){
			include('../../events/includes/api/eventful/EventDelete.php');
		} elseif($row[1] == 2){
			include('../../events/includes/api/eventbrite/EventDelete.php');
		}//end if
	}//end if

	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../../events/cache/lmap' . $curCache . '.php'))){
		unlink('../../events/cache/lmap' . $curCache . '.php');
	}//end if
	if(file_exists(realpath('../../events/cache/sitemap_events.php'))){
		foreach(glob('../../events/cache/sitemap*.*') as $filename){
			unlink($filename);
		}//end foreach
	}//end if

	if(isset($_GET['oID']) OR isset($_POST['oID'])) {
		header("Location: " . CalAdminRoot . "/index.php?com=eventorphan&msg=1");
	} elseif(isset($_POST['pID'])) {
		header("Location: " . CalAdminRoot . "/index.php?com=eventpending&msg=5");
	} elseif(isset($_GET['dpID'])){
		header("Location: " . CalAdminRoot . "/index.php?com=reportdup&msg=1");
	} else {
		header("Location: " . CalAdminRoot . "/index.php?com=eventsearch&sID=2&msg=" . $msgID);
	}//end if
?>