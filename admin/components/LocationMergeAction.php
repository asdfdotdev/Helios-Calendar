<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
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
	
	header('Location: ' . CalAdminRoot . '/index.php?com=location&msg=' . $msgID);	?>