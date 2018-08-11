<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
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
		$result = doQuery("UPDATE " . HC_TblPrefix . "oidusers SET ShortName = '" . cIn($oidName) . "' WHERE PkID = '" . $_SESSION[$hc_cfg00 . 'hc_OpenIDPkID'] . "'");
	}//end if

	header("Location: " . CalRoot . "/index.php?com=oacc&msg=" . $msg);
?>