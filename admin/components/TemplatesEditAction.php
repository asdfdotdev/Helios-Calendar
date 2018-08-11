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
	
	if(!isset($_GET['dID'])){
		$tID = $_POST['tID'];
		$name = $_POST['name'];
		$content = $_POST['content'];
		$header = $_POST['header'];
		$footer = $_POST['footer'];
		$ext = $_POST['ext'];
		$typeID = $_POST['typeID'];
		$groupBy = $_POST['group'];
		$sortBy = $_POST['sort'];
		$dateFormat = $_POST['dateFormat'];
		$cleanup = $_POST['cleanup']; 
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templates WHERE PkID = '" . cIn($tID) . "' AND IsActive = 1");
		if(hasRows($result)){
			$msgID = 1;
			doQuery("UPDATE " . HC_TblPrefix . "templates
						SET Name = '" . cIn($name) . "',
							Content = '" . cIn($content) . "',
							Header = '" . cIn($header) . "',
							Footer = '" . cIn($footer) . "',
							Extension = '" . cIn($ext) . "',
							TypeID = '" . cIn($typeID) . "',
							GroupBy = '" . cIn($groupBy) . "',
							SortBy = '" . cIn($sortBy) . "',
							CleanUp = '" . cIn($cleanup) . "',
							DateFormat = '" . cIn($dateFormat) . "'
						WHERE PkID = " . cIn($tID));
		} else {
			$msgID = 2;
			doQuery("INSERT INTO " . HC_TblPrefix . "templates(Name, Content, Header, Footer, Extension, TypeID, GroupBy, SortBy, DateFormat, CleanUp, IsActive)
					VALUES( '" . cIn($name) . "',
							'" . cIn($content) . "',
							'" . cIn($header) . "',
							'" . cIn($footer) . "',
							'" . cIn($ext) . "',
							'" . cIn($typeID) . "',
							'" . cIn($groupBy) . "',
							'" . cIn($sortBy) . "',
							'" . cIn($dateFormat) . "',
							'" . cIn($cleanup) . "',
							1)");
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "locations");
			$lID = mysql_result($result,0,0);
		}//end if
	} else {
		$msgID = 3;
		doQuery("UPDATE " . HC_TblPrefix . "templates SET IsActive = 0 WHERE PkID = '" . cIn($_GET['dID']) . "'");
		doQuery("UPDATE " . HC_TblPrefix . "events SET LocationName = 'Unknown', LocID = 0 WHERE LocID = '" . cIn($_GET['dID']) . "'");
	}//end if	
	
	header('Location: ' . CalAdminRoot . '/index.php?com=exporttmplts&msg=' . $msgID);?>