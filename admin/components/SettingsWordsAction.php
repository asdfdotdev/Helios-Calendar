<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
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
	
	clearCache();
		
	header("Location: " . CalAdminRoot . "/index.php?com=words&msg=1");?>