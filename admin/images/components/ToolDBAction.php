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
	
	$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	if(hasRows($result)){
		$deleteUs = "0";
		while($row = mysql_fetch_row($result)){
			$deleteUs .= "," . $row[0];
		}//end while
		doQuery("DELETE FROM " . HC_TblPrefix . "eventnetwork WHERE EventID IN (" . $deleteUs . ")");
	}//end if
	
	doQuery("DELETE FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	doQuery("DELETE FROM " . HC_TblPrefix . "locations WHERE IsActive = 0");
	doQuery("DELETE " . HC_TblPrefix . "eventcategories ec FROM " . HC_TblPrefix . "eventcategories ec LEFT JOIN " . HC_TblPrefix . "events e ON (ec.EventID = e.PkID) WHERE e.PkID is NULL OR e.IsActive = 0 OR e.IsApproved = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "categories WHERE IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "oidusers WHERE IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "comments WHERE IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "commentsreportlog WHERE IsActive = 0");
	
	header('Location: ' . CalAdminRoot . '/index.php?com=db&msg=1');?>