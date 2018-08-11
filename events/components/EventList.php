<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/event.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1":
				feedback(1,$hc_lang_event['Feed02']);
				break;
			case "2":
				feedback(2,$hc_lang_event['Feed03']);
				break;
			case "3":
				feedback(2,$hc_lang_event['Feed10']);
				break;
		}//end switch
	}//end if
	
	$hourOffset = date("G") + ($hc_cfg35);
	$sysDate = date("Y-m-d", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	$dayView = false;
	
	if(!isset($_GET['theDate'])){
		$sysDateParts = explode("-",$sysDate);
		if(isset($_GET['day']) && is_numeric($_GET['day'])){
			$day = $_GET['day'];
			$dayView = true;
		} elseif(isset($_GET['theDate'])) {
			$datePart = explode("-", $_GET['theDate']);
			$day = $datePart[2];
		} else {
			$day = $sysDateParts[2];
		}//end if
		
		$year = (isset($_GET['year']) && is_numeric($_GET['year'])) ? $_GET['year'] : $sysDateParts[0];
		
		$month = $sysDateParts[1];
		if(isset($_GET['month']) && is_numeric($_GET['month'])){
			if(!isset($_GET['day']) && ($_GET['month'] > $month || $_GET['year'] > $year)){
				$day = 1;
			}//end if
			$month = $_GET['month'];
		}//end if
	} else {
		$passDateParts = explode("-",$_GET['theDate']);
		$year = $passDateParts[0];
		$month = $passDateParts[1];
		$day = $passDateParts[2];
	}//end if
	
	$builtDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
	if(!checkdate($month, $day, $year) || (strtotime($sysDate) > strtotime($builtDate) && $hc_cfg11 == 0) ){
		$sysDateParts = explode("-",$sysDate);
		$year = $sysDateParts[0];
		$month = $sysDateParts[1];
		$day = $sysDateParts[2];
		$builtDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$dayView = false;
		
		$goErr = ($hc_cfg11 == 0) ? $hc_lang_event['NoPastDates'] : $hc_lang_event['InvalidDate'];
		feedback(2, $goErr);
	}//end if
	
	if(isset($_GET['lID']) && is_numeric($_GET['lID'])){
		include('components/LocationDetail.php');
		
		$locQuery = " AND LocID = '" . cIn($locID) . "'";
		$locSaver = "&lID=" . cIn($locID);
		$mnuLoc = "?" . substr($locSaver,1);
	} else {
		$locSaver = "";
		$locQuery = "";
		$mnuLoc = "";
	}//end if
	
	$remove = (date("w", mktime(0,0,0,$month,$day,$year)) + 6) % 7;
	$startDate = ($builtDate == $sysDate) ? $sysDate : date("Y-m-d", mktime(0, 0, 0, $month, $day - $remove, $year));
	
	if($hc_cfg34 == 0){
		$lType = $hc_lang_event['Week'];
		$stopDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 6, $year));
		$prevDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) - 7, $year));
		$nextDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 7, $year));
		
		$prevPart = explode("-", $prevDate);
		$prevMonth = $prevPart[1];
		$prevYear = $prevPart[0];
		
		$nextPart = explode("-", $nextDate);
		$nextMonth = $nextPart[1];
		$nextYear = $nextPart[0];
		
		$prevLink =  CalRoot . "/?theDate=" . $prevDate . "&amp;year=" . $prevYear . "&amp;month=" . $prevMonth;
		$nextLink = CalRoot . "/?theDate=" . $nextDate . "&amp;year=" . $nextYear . "&amp;month=" . $nextMonth;
	} else {
		$lType = $hc_lang_event['Month'];
		$stopDate = date("Y-m-d", mktime(0, 0, 0, $month + 1, 0, $year));
		$prevLink = CalRoot . "?year=" . date("Y", mktime(0, 0, 0, $month - 1, 1, $year)) . "&amp;month=" . date("m", mktime(0, 0, 0, $month -1, 1, $year));
		$nextLink = CalRoot . "?year=" . date("Y", mktime(0, 0, 0, $month + 1, 1, $year)) . "&amp;month=" . date("m", mktime(0, 0, 0, $month + 1, 1, $year));
	}//end if
	
	$query = "	SELECT DISTINCT e.*
				FROM " . HC_TblPrefix . "events e
					LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
					LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)";
	$query .= ($dayView) ? 
			" WHERE e.StartDate = '" . $builtDate . "'" : 
			" WHERE e.StartDate BETWEEN '" . cIn($startDate) . "' AND '" . cIn($stopDate) . "'";
	$query .= $locQuery;
	$query .= "	AND e.IsActive = 1 
				AND e.IsApproved = 1 ";
	
	if(isset($_SESSION[$hc_cfg00 . 'hc_favCat']) && $_SESSION[$hc_cfg00 . 'hc_favCat'] != ''){
		$query = $query . " AND ec.CategoryID in (" . $_SESSION[$hc_cfg00 . 'hc_favCat'] . ") ";
	}//end if
	
	if(isset($_SESSION[$hc_cfg00 . 'hc_favCity']) && $_SESSION[$hc_cfg00 . 'hc_favCity'] != ''){
		$query = $query . " AND (e.LocationCity IN (" . $_SESSION[$hc_cfg00 . 'hc_favCity'] . ") OR l.City IN (" . $_SESSION[$hc_cfg00 . 'hc_favCity'] . "))";
	}//end if
	
	$query .= " ORDER BY e.StartDate, e.TBD, e.StartTime, e.Title";
	$result = doQuery($query);	
	
	echo '<div id="nav-top">';
	echo '<a href="' . CalRoot . '/index.php?com=filter" title="' . $hc_lang_event['Filter'] . '">';
	echo (isset($_SESSION[$hc_cfg00 . 'hc_favCat']) || isset($_SESSION[$hc_cfg00 . 'hc_favCity'])) ? 
		'<img src="' . CalRoot . '/images/nav/filterR.png" alt="" border="0" /></a>':
		'<img src="' . CalRoot . '/images/nav/filter.png" alt="" border="0" /></a>';
	echo ' <a href="' . CalRoot . '/' . $mnuLoc . '" title="' . $hc_lang_event['Home'] . '"><img src="' . CalRoot . '/images/nav/home.png" alt="" border="0" /></a>';
	echo ' <a href="' . $prevLink . $locSaver . '" title="' . $hc_lang_event['BrowseBack'] . ' ' . $lType . '"><img src="' . CalRoot . '/images/nav/left.png" alt="" border="0" /></a>';
	echo ' <a href="' . $nextLink . $locSaver . '" title="' . $hc_lang_event['BrowseForward'] . ' ' . $lType . '"><img src="' . CalRoot . '/images/nav/right.png" alt="" border="0" /></a>';
	echo '</div>';
	
	if(hasRows($result)){
		$cnt = 0;
		$curDate = "";
		$calSaver = "&amp;year=" . $year . "&amp;month=" . $month . $locSaver;
		while($row = mysql_fetch_row($result)){
			if(($curDate != $row[9]) or ($cnt == 0)){
				$curDate = $row[9];
				echo '<div class="eventDateTitle">' . stampToDate($row[9], $hc_cfg14) . '</div>';
		 		$cnt = 0;
			}//end if
			
			echo ($cnt % 2 == 1) ? '<div class="eventListTimeHL">' : '<div class="eventListTime">';
			$startTime = "";
			$endTime = "";
			if($row[10] != ''){
				$timepart = explode(":", $row[10]);
				$startTime = strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
			}//end if
			
			if($row[12] != ''){
				$timepart = explode(":", $row[12]);
				$endTime = '&nbsp;-&nbsp;' . strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
			}//end if
			
			if($row[11] == 0){
				echo $startTime . $endTime;
			} elseif($row[11] == 1) {
				echo "<i>" . $hc_lang_event['AllDay'] . "</i>";
			} elseif($row[11] == 2) {
				echo "<i>" . $hc_lang_event['TBA'] . "</i>";
			}//end if
			echo '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="eventListTitleHL">' : '<div class="eventListTitle">';
			echo '<a href="' . CalRoot . '/index.php?com=detail&amp;eID=' . $row[0] . $calSaver . '" class="eventListTitle">' . cOut($row[1]) . '</a></div>';
 			
			++$cnt;
		}//end while
	} else {
		echo $hc_lang_event['NoEventBrowse'];
		echo '<ol>';
		echo '<li style="line-height: 30px;">' . $hc_lang_event['NoEvent1'] . '<span class="miniCalEvents" style="padding:3px;">03</span></li>';
		echo '<li style="line-height: 30px;">' . $hc_lang_event['NoEvent2'] . '<span class="miniCalNav" style="padding:3px;">&lt;</span> <span class="miniCalNav" style="padding:3px;">&gt;</span></li>';
	
		echo (isset($_SESSION[$hc_cfg00 . 'hc_favCat']) || isset($_SESSION[$hc_cfg00 . 'hc_favCity'])) ? '<li>' . $hc_lang_event['NoEvent4'] . ' <a href="' . CalRoot . '/index.php?com=filter" class="eventMain"><img src="' . CalRoot . '/images/nav/filterR.png" width="16" height=\"16\" alt="" style="vertical-align:bottom;padding-top:10px;" /></a></li>' :'';
		echo "</ol>";
	}//end if	
	
	echo '<div id="nav-bottom">';
	echo '<a href="' . CalRoot . '/index.php?com=filter" title="' . $hc_lang_event['Filter'] . '">';
	echo (isset($_SESSION[$hc_cfg00 . 'hc_favCat']) || isset($_SESSION[$hc_cfg00 . 'hc_favCity'])) ? 
		'<img src="' . CalRoot . '/images/nav/filterR.png" alt="" border="0" /></a>':
		'<img src="' . CalRoot . '/images/nav/filter.png" alt="" border="0" /></a>';
	echo ' <a href="' . CalRoot . '/' . $mnuLoc . '" title="' . $hc_lang_event['Home'] . '"><img src="' . CalRoot . '/images/nav/home.png" alt="" border="0" /></a>';
	echo ' <a href="' . $prevLink . $locSaver . '" title="' . $hc_lang_event['BrowseBack'] . ' ' . $lType . '"><img src="' . CalRoot . '/images/nav/left.png" alt="" border="0" /></a>';
	echo ' <a href="' . $nextLink . $locSaver . '" title="' . $hc_lang_event['BrowseForward'] . ' ' . $lType . '"><img src="' . CalRoot . '/images/nav/right.png" alt="" border="0" /></a>';
	echo '</div>';?>