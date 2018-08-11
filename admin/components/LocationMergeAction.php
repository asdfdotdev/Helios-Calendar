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
	checkIt(1);
	
	$locIDs = cIn($_POST['locIDs']);
	$msgID = 11;
	if(is_numeric($_POST['mergeID'][0])){
		$msgID = 12;
		doQuery("UPDATE " . HC_TblPrefix . "events SET LocID = '" . cIn($_POST['mergeID'][0]) . "' WHERE LocID IN (" . $locIDs . ")");
		doQuery("UPDATE " . HC_TblPrefix . "locations SET IsActive = 0 WHERE PkID IN (" . cIn($locIDs) . ") AND PkID != '" . cIn($_POST['mergeID'][0]) . "'");
	}//end if
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../../events/cache/lmap' . $curCache . '.php'))){
		unlink('../../events/cache/lmap' . $curCache . '.php');
	}//end if
	
	header('Location: ' . CalAdminRoot . '/index.php?com=location&msg=' . $msgID);	?>