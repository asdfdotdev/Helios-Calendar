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
	
	$allowIndex = $_POST['indexing'];
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . cIn($allowIndex) . "' WHERE PkID = '7'");
	
	$keywords = (isset($_POST['keywords'])) ? $_POST['keywords'] : array();
	$descriptions = (isset($_POST['descriptions'])) ? $_POST['descriptions'] : array();
	$titles = (isset($_POST['titles'])) ? $_POST['titles'] : array();
	$cnt = 0;
	
	foreach ($keywords as $val){
		doQuery("UPDATE " . HC_TblPrefix . "settingsmeta
				SET Keywords = '" . cIn(strip_tags($keywords[$cnt])) . "',
				Description = '" . cIn(strip_tags($descriptions[$cnt])) . "',
				Title = '" . cIn(strip_tags($titles[$cnt])) . "'
				WHERE PkID = '" . ($cnt + 1) . "'");
		++$cnt;
	}//end foreach
	
	if(file_exists(realpath('../../events/cache/config.php'))){
		unlink('../../events/cache/config.php');
	}//end if
	if(file_exists(realpath('../../events/cache/meta.php'))){
		unlink('../../events/cache/meta.php');
	}//end if

	header("Location: " . CalAdminRoot . "/index.php?com=optimize&msg=1");?>