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
	checkIt(1);
	
	$locIDs = cIn($_POST['locIDs']);
	$msgID = 11;
	if(is_numeric($_POST['mergeID'][0])){
		$msgID = 12;
		doQuery("UPDATE " . HC_TblPrefix . "events SET LocID = '" . cIn($_POST['mergeID'][0]) . "' WHERE LocID IN (" . $locIDs . ")");
		doQuery("UPDATE " . HC_TblPrefix . "locations SET IsActive = 0 WHERE PkID IN (" . cIn($locIDs) . ") AND PkID != '" . cIn($_POST['mergeID'][0]) . "'");
	}//end if
	
	clearCache();
	
	header('Location: ' . CalAdminRoot . '/index.php?com=location&msg=' . $msgID);	?>