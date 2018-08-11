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
	
	$wordList = trim($_POST['wordList']);
	$wordListA = explode("\n",$wordList);
	$wordList = sort($wordListA);
	$wordList = implode("\n",$wordListA);
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $wordList . "' WHERE PkID = 55");
	
	clearCache();
		
	header("Location: " . CalAdminRoot . "/index.php?com=words&msg=1");?>