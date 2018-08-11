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
	
	if(!isset($_GET['dID'])){
		$cID = $_POST['cID'];
		$name = $_POST['name'];
		$parentID = $_POST['parentID'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE PkID = " . cIn($cID));
		
		if(hasRows($result)){
			if(mysql_result($result,0,2) == 0 && $parentID != 0){
				doQuery("UPDATE " . HC_TblPrefix . "categories SET ParentID = 0 WHERE ParentID = '" . cIn($cID) . "'");
			}//end if
			
			doQuery("UPDATE " . HC_TblPrefix . "categories SET CategoryName = '" . cIn($name) . "', ParentID = '" . cIn($parentID) . "' WHERE PkID = '" . cIn($cID) . "'");
			$msg = 1;
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "categories(CategoryName, ParentID, IsActive) VALUES('" . cIn($name) . "', '" . cIn($parentID) . "', 1)");
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "categories");
			$cID = mysql_result($result,0,0);
			$msg = 2;
		}//end if
		
		header('Location: ' . CalAdminRoot . '/index.php?com=categorymanage&msg=' . $msg . '&cID=' . cIn($cID));
	} else {
		doQuery("UPDATE " . HC_TblPrefix . "categories SET ParentID = 0 WHERE ParentID = '" . cIn($_GET['dID']) . "'");
		doQuery("UPDATE " . HC_TblPrefix . "categories SET IsActive = 0 WHERE PkID = '" . cIn($_GET['dID']) . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE CategoryID = '" . cIn($_GET['dID']) . "'");
		
		header('Location: ' . CalAdminRoot . '/index.php?com=categorymanage&msg=3');
	}//end if	?>