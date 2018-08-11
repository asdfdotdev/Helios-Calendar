<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	$eID = $_GET['eID'];
	doQuery("UPDATE " . HC_TblPrefix . "events SET IsBillboard = 0 WHERE PkID = '" . cIn($eID) . "'");
	header('Location: ' . CalAdminRoot . '/index.php?com=eventbillboard&msg=1');
?>