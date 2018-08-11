<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$pID = (isset($_GET['pID']) && is_numeric($_GET['pID'])) ? cIn(strip_tags($_GET['pID'])) : 0;

	$result = doQuery("SELECT TemplateSource FROM " . HC_TblPrefix . "templatesnews WHERE PkID = '" . $pID . "'");
	echo (hasRows($result)) ? cOut(mysql_result($result,0,0)) : $hc_lang_news['InvalidTemplate'];
?>