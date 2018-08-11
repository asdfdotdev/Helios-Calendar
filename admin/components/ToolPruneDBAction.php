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
	
	$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	if(hasRows($result)){
		$deleteUs = "0";
		while($row = mysql_fetch_row($result)){
			$deleteUs .= "," . $row[0];
		}//end while
		doQuery("DELETE FROM " . HC_TblPrefix . "eventnetwork WHERE EventID IN (" . $deleteUs . ")");
	}//end if
	
	doQuery("DELETE FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	header('Location: ' . CalAdminRoot . '/index.php?com=db&msg=1');	?>