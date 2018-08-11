<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	if(!isset($_GET['dID'])){
		$cID = (isset($_POST['cID']) && is_numeric($_POST['cID'])) ? cIn($_POST['cID']) : 0;
		$name = (isset($_POST['name'])) ? cIn($_POST['name']) : '';
		$parentID = (isset($_POST['parentID']) && is_numeric($_POST['parentID'])) ? cIn($_POST['parentID']) : 0;
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE PkID = '" . cIn($cID) . "'");
		if(hasRows($result)){
			if(mysql_result($result,0,2) == 0 && $parentID != 0){
				doQuery("UPDATE " . HC_TblPrefix . "categories SET ParentID = 0 WHERE ParentID = '" . $cID . "'");
			}//end if
			
			doQuery("UPDATE " . HC_TblPrefix . "categories SET CategoryName = '" . $name . "', ParentID = '" . $parentID . "' WHERE PkID = '" . $cID . "'");
			$msg = 1;
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "categories(CategoryName, ParentID, IsActive) VALUES('" . $name . "', '" . $parentID . "', 1)");
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "categories");
			$cID = mysql_result($result,0,0);
			$msg = 2;
		}//end if
		
		header('Location: ' . CalAdminRoot . '/index.php?com=categorymanage&msg=' . $msg . '&cID=' . $cID);
	} else {
		$dID = (isset($_GET['dID']) && is_numeric($_GET['dID'])) ? cIn($_GET['dID']) : 0;
		doQuery("UPDATE " . HC_TblPrefix . "categories SET ParentID = 0 WHERE ParentID = '" . $dID . "'");
		doQuery("UPDATE " . HC_TblPrefix . "categories SET IsActive = 0 WHERE PkID = '" . $dID . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE CategoryID = '" . $dID . "'");
		
		header('Location: ' . CalAdminRoot . '/index.php?com=categorymanage&msg=3');
	}//end if	?>