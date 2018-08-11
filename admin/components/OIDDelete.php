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
	
	$msgID = 1;
	$dID = (isset($_GET['dID']) && is_numeric($_GET['dID'])) ? cIn(abs($_GET['dID'])) : 0;
	$tID = (isset($_GET['tID']) && is_numeric($_GET['tID'])) ? cIn(abs($_GET['tID'])) : 0;
	
	if($tID == 1){
		doQuery("UPDATE " . HC_TblPrefix . "oidusers SET IsActive = 0 WHERE PkID = '" . cIn($dID) . "'");
		doQuery("UPDATE " . HC_TblPrefix . "comments SET IsActive = 0 WHERE PosterID = '" . cIn($dID) . "'");
		doQuery("UPDATE " . HC_TblPrefix . "commentsreportlog SET IsActive = 0 WHERE UserID = '" . cIn($dID) . "'");
		$result = doQuery("SELECT CommentID FROM " . HC_TblPrefix . "recomndslog WHERE OIDUser = '" . cIn($dID) . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "recomndslog WHERE OIDUser = '" . cIn($dID) . "'");
	} elseif($tID == 2){
		$msgID = 2;
		doQuery("UPDATE " . HC_TblPrefix . "oidusers SET IsActive = 2 WHERE PkID = '" . cIn($dID) . "'");
		doQuery("UPDATE " . HC_TblPrefix . "comments SET IsActive = 0 WHERE PosterID = '" . cIn($dID) . "'");
		doQuery("UPDATE " . HC_TblPrefix . "commentsreportlog SET IsActive = 0 WHERE UserID = '" . cIn($dID) . "'");
		$result = doQuery("SELECT CommentID FROM " . HC_TblPrefix . "recomndslog WHERE OIDUser = '" . cIn($dID) . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "recomndslog WHERE OIDUser = '" . cIn($dID) . "'");
	} elseif($tID == 3){
		$msgID = 3;
		doQuery("UPDATE " . HC_TblPrefix . "oidusers SET IsActive = 1 WHERE PkID = '" . cIn($dID) . "'");
	}//end if
	
	if(isset($result) && hasRows($result)){
		while($row = mysql_fetch_row($result)){
			$resultS = doQuery("SELECT SUM(rl.Score) FROM " . HC_TblPrefix . "recomndslog rl WHERE rl.CommentID = '" . cIn($row[0]) . "'");
			if(mysql_result($resultS,0,0) == ''){
				doQuery("UPDATE " . HC_TblPrefix . "comments c SET c.Recomnds = 0 WHERE c.PkID = '" . cIn($row[0]) . "'");
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "comments c SET c.Recomnds = '" . cIn(mysql_result($resultS,0,0)) . "' WHERE c.PkID = '" . cIn($row[0]) . "'");
			}//end if
		}//end if
	}//end if
	
	header('Location: ' . CalAdminRoot . '/index.php?com=oiduser&msg=' . $msgID);?>