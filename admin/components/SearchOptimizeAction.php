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
	
	$allowIndex = $_POST['indexing'];
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($allowIndex) . "' WHERE PkID = '7'");

	$ids = (isset($_POST['ids'])) ? $_POST['ids'] : array();
	$keywords = (isset($_POST['keywords'])) ? $_POST['keywords'] : array();
	$descriptions = (isset($_POST['descriptions'])) ? $_POST['descriptions'] : array();
	$titles = (isset($_POST['titles'])) ? $_POST['titles'] : array();
	$cnt = 0;

	foreach ($keywords as $val){
		doQuery("UPDATE " . HC_TblPrefix . "settingsmeta
				SET Keywords = '" . cIn(strip_tags($keywords[$cnt])) . "',
				Description = '" . cIn(strip_tags($descriptions[$cnt])) . "',
				Title = '" . cIn(strip_tags($titles[$cnt])) . "'
				WHERE PkID = '" . cIn(strip_tags($ids[$cnt])) . "'");
		++$cnt;
	}//end foreach
	
	clearCache();
	
	header("Location: " . CalAdminRoot . "/index.php?com=optimize&msg=1");?>