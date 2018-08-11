<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
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