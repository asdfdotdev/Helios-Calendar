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
	
	$msgID = 2;
	$dID = (isset($_GET['dID']) && is_numeric($_GET['dID'])) ? cIn(abs($_GET['dID'])) : 0;
	$uID = (isset($_GET['uID']) && is_numeric($_GET['uID'])) ? cIn(abs($_GET['uID'])) : 0;
	$tID = (isset($_GET['tID']) && is_numeric($_GET['tID'])) ? cIn(abs($_GET['tID'])) : 0;
	$redURLcom = ($tID > 0) ? 'cmntrep' : 'cmntmgt'; 
	$redURLu = ($uID > 0) ? '&uID=' . $uID : '';
	
	doQuery("UPDATE " . HC_TblPrefix . "commentsreportlog SET IsActive = 0 WHERE CommentID = '" . cIn($dID) . "'");
	if($tID < 2){
		$msgID = 1;
		doQuery("UPDATE " . HC_TblPrefix . "comments SET IsActive = 0 WHERE PkID = '" . cIn($dID) . "'");
	}//end if
	
	header('Location: ' . CalAdminRoot . '/index.php?com=' . $redURLcom . '&msg=' . $msgID . $redURLu);?>