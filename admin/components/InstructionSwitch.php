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
	
	$_SESSION['Instructions'] = ($_SESSION['Instructions'] == 1) ? 0 : 1;	
	doQuery("UPDATE " . HC_TblPrefix . "admin SET ShowInstructions = " . $_SESSION['Instructions'] . " WHERE PkID = " . $_SESSION['AdminPkID']);
	
	header("Location: " . $_SERVER['HTTP_REFERER']);
?>