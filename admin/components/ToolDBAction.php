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
	
	$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	if(hasRows($result)){
		$deleteUs = "0";
		while($row = mysql_fetch_row($result)){
			$deleteUs .= "," . $row[0];
		}//end while
		doQuery("DELETE FROM " . HC_TblPrefix . "eventnetwork WHERE EventID IN (" . $deleteUs . ")");
	}//end if
	
	doQuery("DELETE FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	doQuery("DELETE en FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE e.PkID IS NULL OR e.IsActive = 0 OR e.IsApproved = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "locations WHERE IsActive = 0");
	doQuery("DELETE ln FROM " . HC_TblPrefix . "locationnetwork ln LEFT JOIN " . HC_TblPrefix . "locations l ON (l.PkID = ln.LocationID) WHERE l.PkID IS NULL OR l.IsActive = 0");
	doQuery("DELETE ec FROM " . HC_TblPrefix . "eventcategories ec LEFT JOIN " . HC_TblPrefix . "events e ON (ec.EventID = e.PkID) WHERE e.PkID is NULL OR e.IsActive = 0 OR e.IsApproved = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "categories WHERE IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "oidusers WHERE IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "comments WHERE IsActive = 0");
	doQuery("DELETE FROM " . HC_TblPrefix . "commentsreportlog WHERE IsActive = 0");
     doQuery("DELETE FROM " . HC_TblPrefix . "templates WHERE IsActive = 0");
	doQuery("DELETE rl FROM " . HC_TblPrefix . "recomndslog rl LEFT JOIN " . HC_TblPrefix . "oidusers oid ON (oid.PkID = rl.OIDUser) WHERE oid.PkID is NULL OR oid.IsActive != 1");
	
	header('Location: ' . CalAdminRoot . '/index.php?com=db&msg=1');?>