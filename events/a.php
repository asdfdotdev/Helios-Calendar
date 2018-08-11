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
	include('includes/include.php');
	
	$GUID = (isset($_GET['a'])) ? cIn($_GET['a']) : '';
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE GUID = '" . $GUID . "'");
	if(hasRows($result) && mysql_result($result,0,6) == 0){
		doQuery("UPDATE " . HC_TblPrefix . "subscribers SET IsConfirm = 1 WHERE PkID = '" . mysql_result($result,0,0) . "'");
		header('Location: ' . CalRoot . "/index.php?com=signup&t=3");
	} else {
		header('Location: ' . CalRoot . '/');
	}//end if
?>