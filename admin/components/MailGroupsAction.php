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
		$gID = isset($_POST['gID']) ? cIn($_POST['gID']) : 0;
		$name = isset($_POST['name']) ? cIn($_POST['name']) : '';
		$description = isset($_POST['description']) ? cIn(cleanQuotes($_POST['description']),1) : '';
		$status = isset($_POST['status']) ? cIn($_POST['status']) : '';
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "mailgroups WHERE PkID = '" . cIn($gID) . "'");
		if(hasRows($result)){
			doQuery("UPDATE " . HC_TblPrefix . "mailgroups
					SET Name = '" . $name . "',
						Description = '" . $description . "',
						IsPublic = '" . $status . "'
					WHERE PkID = '" . $gID . "'");
			header("Location: " . CalAdminRoot . "/index.php?com=subgrps&msg=2");
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "mailgroups(Name,Description,IsPublic,IsActive)
					Values(	'" . $name . "',
							'" . $description . "',
							'" . $status . "',1)");
			header("Location: " . CalAdminRoot . "/index.php?com=subgrps&msg=3");
		}//end if
	} else {
		if($_GET['dID'] != 1){
			doQuery("UPDATE " . HC_TblPrefix . "mailgroups SET IsActive = 0 WHERE PkiD = '" . cIn($_GET['dID']) . "'");
		}//end if
		header("Location: " . CalAdminRoot . "/index.php?com=subgrps&msg=1");
	}//end if	?>