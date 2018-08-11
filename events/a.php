<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include('includes/include.php');
	
	if(isset($_GET['a'])){
		$GUID = $_GET['a'];
	} else {
		header('Location: ' . CalRoot . '/');
	}//end if
	
	$query = "SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . $GUID . "'";
	$result = mysql_query($query) or die(mysql_error());
	
	if(hasRows($result) && mysql_result($result,0,6) == 0){
		doQuery("UPDATE " . HC_TblPrefix . "users SET IsRegistered = 1 WHERE PkID = '" . mysql_result($result,0,0) . "'");
		header('Location: ' . CalRoot . "/index.php?com=signup&msg=3");
	} else {
		header('Location: ' . CalRoot . '/');
	}//end if?>