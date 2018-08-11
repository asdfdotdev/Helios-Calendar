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
	checkIt(1);
	
	$locIDs = cIn($_POST['locIDs']);
	$msgID = 12;
	if(is_numeric($_POST['mergeID'][0])){
		$msgID = 13;
		doQuery("UPDATE " . HC_TblPrefix . "events SET LocID = '" . cIn($_POST['mergeID'][0]) . "' WHERE LocID IN (" . $locIDs . ")");
		doQuery("UPDATE " . HC_TblPrefix . "locations SET IsActive = 0 WHERE PkID IN (" . cIn($locIDs) . ") AND PkID != '" . cIn($_POST['mergeID'][0]) . "'");
	}//end if
	
	header('Location: ' . CalAdminRoot . '/index.php?com=location&msg=' . $msgID);	?>