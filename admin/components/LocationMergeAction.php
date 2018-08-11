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
	post_only();
	
	$locIDs = array_filter(explode(',',$_POST['locIDs']),'is_numeric');
	$locIDs = cIn(implode(',',$locIDs));
	$msgID = 11;
	
	if(is_numeric($_POST['mergeID'][0])){
		$msgID = 12;
		doQuery("UPDATE " . HC_TblPrefix . "events SET LocID = '" . cIn($_POST['mergeID'][0]) . "' WHERE LocID IN (" . $locIDs . ")");
		doQuery("UPDATE " . HC_TblPrefix . "locations SET IsActive = 0 WHERE PkID IN (" . $locIDs . ") AND PkID != '" . cIn($_POST['mergeID'][0]) . "'");
	}
	
	clearCache();
	
	header('Location: ' . AdminRoot . '/index.php?com=location&msg=' . $msgID);
?>