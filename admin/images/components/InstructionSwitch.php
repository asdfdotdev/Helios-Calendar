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
	
	$_SESSION[$hc_cfg00 . 'Instructions'] = ($_SESSION[$hc_cfg00 . 'Instructions'] == 1) ? 0 : 1;
	
	doQuery("UPDATE " . HC_TblPrefix . "admin SET ShowInstructions = " . $_SESSION[$hc_cfg00 . 'Instructions'] . " WHERE PkID = " . $_SESSION[$hc_cfg00 . 'AdminPkID']);
	
	header("Location: " . $_SERVER['HTTP_REFERER']);	?>