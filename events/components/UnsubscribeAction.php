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
	checkIt();
	
	if(isset($_POST['dID'])){
		$guid = $_POST['dID'];
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . cIn($guid) . "'");
		$uID = mysql_result($result,0,0);
		if(hasRows($result)){
			doQuery("DELETE FROM " . HC_TblPrefix . "users WHERE PkID = " . $uID);
			doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = " . $uID);
			
			header('Location: ' . CalRoot . '/index.php?&msg=1');
			exit();
		}//end if
	}//end if
	
	header('Location: ' . CalRoot . '/');?>