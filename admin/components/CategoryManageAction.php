<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	checkIt(1);
	
	if(isset($_POST['cID']) && is_numeric($_POST['cID'])){
		$cID = $_POST['cID'];
	} else {
		$cID = 0;
	}//end if
	
	if(isset($_POST['name'])){
		$name = $_POST['name'];
	} else {
		$name = "";
	}//end if
	
	if(!isset($_GET['dID'])){
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE PkID = " . cIn($cID));
		$row_cnt = mysql_num_rows($result);
		
		if($row_cnt > 0){
			doQuery("UPDATE " . HC_TblPrefix . "categories SET CategoryName = '" . cIn($name) . "'  WHERE PkID = '" . cIn($cID) . "'");
			
			header('Location: ' . CalAdminRoot . '/index.php?com=categorymanage&msg=1&cID=' . $cID);
			
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "categories(CategoryName, ParentID, IsActive, Path, Level) VALUES('" . cIn($name) . "', 0, 1, '/', 0)");
			
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "categories");
			$cID = mysql_result($result,0,0);
			header('Location: ' . CalAdminRoot . '/index.php?com=categorymanage&msg=2&cID=' . $cID);
			
		}//end if
		
	} else {
		doQuery("UPDATE " . HC_TblPrefix . "categories SET IsActive = 0 WHERE PkID = '" . cIn($_GET['dID']) . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE CategoryID = '" . cIn($_GET['dID']) . "'");
		
		header('Location: ' . CalAdminRoot . '/index.php?com=categorymanage&msg=3');
		
	}//end if
?>