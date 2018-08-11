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
	
	$eID = $_GET['eID'];
	doQuery("UPDATE " . HC_TblPrefix . "events SET IsBillboard = 0 WHERE PkID = '" . cIn($eID) . "'");
	header('Location: ' . CalAdminRoot . '/index.php?com=eventbillboard&msg=1');
?>