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
	include('loader.php');
	
	admin_logged_in();
	action_headers();
	
	$_SESSION['Instructions'] = ($_SESSION['Instructions'] == 1) ? 0 : 1;	
	doQuery("UPDATE " . HC_TblPrefix . "admin SET ShowInstructions = '" . cIn($_SESSION['Instructions']) . "' WHERE PkID = '" . cIn($_SESSION['AdminPkID']) . "'");
	
	if(isset($_SERVER['HTTP_REFERER']) && preg_match('(^'.AdminRoot.')',$_SERVER['HTTP_REFERER']))
		header("Location: " . cIn(strip_tags($_SERVER['HTTP_REFERER'])));
	else
		header("Location: " . AdminRoot);
?>