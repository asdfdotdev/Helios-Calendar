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
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/register.php');
	
	$nID = (isset($_GET['n']) && is_numeric($_GET['n'])) ? cIn(strip_tags($_GET['n'])) : 0;
	$do =  (isset($_GET['d']) && is_numeric($_GET['d'])) ? cIn(strip_tags($_GET['d'])) : 0;
	$go = (isset($_GET['g'])) ? cIn($_GET['g']) : 0;

	switch($do){
		case 1:
			doQuery("UPDATE " . HC_TblPrefix . "newsletters SET Status = 1 WHERE PkID = '" . $nID . "' AND Status != 3");
			break;
		case 2:
			doQuery("UPDATE " . HC_TblPrefix . "newsletters SET Status = 2 WHERE PkID = '" . $nID . "' AND Status != 3");
			break;
	}//end if

	if($go == 1){
		header("Location: " . CalAdminRoot . "/index.php?com=newsqueue&msg=3");
	}//end if
?>
