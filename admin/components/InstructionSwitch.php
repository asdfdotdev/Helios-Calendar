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
	
	if($_SESSION['Instructions'] == 1){
		$_SESSION['Instructions'] = 0;
	} else {
		$_SESSION['Instructions'] = 1;
	}//end if
	
	doQuery("UPDATE " . HC_TblPrefix . "admin SET ShowInstructions = " . $_SESSION['Instructions'] . " WHERE PkID = " . $_SESSION['AdminPkID']);
	
	header("Location: " . $_SERVER['HTTP_REFERER']);
?>