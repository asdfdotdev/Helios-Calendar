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
	
	$seriesString = "0";
	$eventID = $_POST['eventID'];
	$x = 0;
	while($x < count($eventID)){
		$seriesString = $seriesString . "," . $eventID[$x];
		++$x;
	}//end while
	
	$seriesID = DecHex(microtime() * 9999999) . DecHex(microtime() * 5555555) . DecHex(microtime() * 1111111);
	doQuery("UPDATE " . HC_TblPrefix . "events SET SeriesID = '" . $seriesID . "' WHERE PkID IN (" . $seriesString . ")");
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventsearch&sID=3&msg=3");?>