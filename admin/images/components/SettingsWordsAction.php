<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	$wordList = trim($_POST['wordList']);
	$wordListA = explode("\n",$wordList);
	$wordList = sort($wordListA);
	$wordList = implode("\n",$wordListA);
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $wordList . "' WHERE PkID = 55");
	
	if(file_exists(realpath('../../events/cache/censored.php'))){
		unlink('../../events/cache/censored.php');
	}//end if
		
	header("Location: " . CalAdminRoot . "/index.php?com=words&msg=1");?>