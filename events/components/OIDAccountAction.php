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
	checkIt();

	if(!file_exists(realpath('../cache/censored.php'))){
		rebuildCache(2);
	}//end if
	include('../cache/censored.php');

	$oidName = ($_POST['oidName'] != '') ? $_POST['oidName'] : '';
	if(censorWords($oidName,$hc_censored_words) != $oidName){
		$msg = 2;
	} else {
		$msg = 1;
		$result = doQuery("UPDATE " . HC_TblPrefix . "oidusers SET ShortName = '" . cIn($oidName) . "' WHERE PkID = '" . $_SESSION['hc_OpenIDPkID'] . "'");
	}//end if

	header("Location: " . CalRoot . "/index.php?com=oacc&msg=" . $msg);
?>