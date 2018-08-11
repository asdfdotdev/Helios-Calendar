<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$dID = (isset($_GET['dID']) && is_numeric($_GET['dID'])) ? cIn($_GET['dID']) : 0;
	
	if($dID > 0)
		doQuery("DELETE FROM " . HC_TblPrefix . "followup WHERE PkID = '" . $dID . "'");
	
	header("Location: " . AdminRoot . "/index.php?com=reportfollow&msg=1");
?>