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
	
	$eID = $_GET['eID'];
	doQuery("UPDATE " . HC_TblPrefix . "events SET IsBillboard = 0 WHERE PkID = '" . cIn($eID) . "'");
	header('Location: ' . CalAdminRoot . '/index.php?com=eventbillboard&msg=1');
?>