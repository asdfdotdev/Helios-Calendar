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
	
	$_SESSION[$hc_cfg00 . 'Instructions'] = ($_SESSION[$hc_cfg00 . 'Instructions'] == 1) ? 0 : 1;
	
	doQuery("UPDATE " . HC_TblPrefix . "admin SET ShowInstructions = " . $_SESSION[$hc_cfg00 . 'Instructions'] . " WHERE PkID = " . $_SESSION[$hc_cfg00 . 'AdminPkID']);
	
	header("Location: " . $_SERVER['HTTP_REFERER']);	?>