<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
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
	$month = $day = $year = 0;
	$oneDay = (isset($_GET['m']) && cIn($_GET['m']) == '1') ? true : false;
	
	if(isset($_GET['d'])){
		$passDateParts = explode("-",$_GET['d']);
		$year = (isset($passDateParts[0]) && is_numeric($passDateParts[0])) ? $passDateParts[0] : $year;
		$month = (isset($passDateParts[1]) && is_numeric($passDateParts[1])) ? $passDateParts[1] : $month;
		$day = (isset($passDateParts[2]) && is_numeric($passDateParts[2])) ? $passDateParts[2] : $day;
	}//end if

	$builtDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
	if(!checkdate($month, $day, $year) || (strtotime($sysDate) > strtotime($builtDate) && $hc_cfg11 == 0) ){
		$sysDateParts = explode("-",$sysDate);
		$year = $sysDateParts[0];
		$month = $sysDateParts[1];
		$day = $sysDateParts[2];
		$builtDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
	}//end if

	$locSaver = $locQuery = $mnuLoc = "";
	if(isset($_GET['lID']) && is_numeric($_GET['lID'])){
		include('components/LocationDetail.php');
		
		$locQuery = " AND LocID = '" . cIn($locID) . "'";
		$locSaver = "&lID=" . cIn($locID);
		$mnuLoc = "?" . substr($locSaver,1);
	}//end if
	
	$remove = (date("w", mktime(0,0,0,$month,$day,$year)) + 6) % 7;
	$startDate = ($builtDate == $sysDate) ? $sysDate : date("Y-m-d", mktime(0, 0, 0, $month, $day - $remove, $year));
	
	if($_SESSION[$hc_cfg00 . 'BrowseType'] == 0){
		$lType = $hc_lang_event['Week'];
		$stopDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 6, $year));
		$prevLink =  CalRoot . "/index.php?d=" . date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) - 7, $year));
		$nextLink = CalRoot . "/index.php?d=" . date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 7, $year));
	} else {
		$lType = $hc_lang_event['Month'];
		$stopDate = date("Y-m-d", mktime(0, 0, 0, $month + 1, 0, $year));
		$prevLink =  CalRoot . "/index.php?d=" . date("Y-m-d", mktime(0, 0, 0, ($month - 1), 1, $year));
		$nextLink = CalRoot . "/index.php?d=" . date("Y-m-d", mktime(0, 0, 0, ($month + 1), 1, $year));
	}//end if
	
	$query = "SELECT DISTINCT e.*
			FROM " . HC_TblPrefix . "events e
				LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
				LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)";
	$query .= ($oneDay) ?
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
	echo '<a href="' . CalRoot . '/index.php?b=0' . $locSaver . '" title="' . $hc_lang_event['ALTBrowseW'] . '"><img src="' . CalRoot . '/images/nav/weekly.png" alt="' . $hc_lang_event['ALTBrowseW'] . '" /></a>';
	echo ' <a href="' . CalRoot . '/index.php?b=1' . $locSaver . '" title="' . $hc_lang_event['ALTBrowseM'] . '"><img src="' . CalRoot . '/images/nav/monthly.png" alt="' . $hc_lang_event['ALTBrowseM'] . '" /></a>';
	echo '&nbsp;&nbsp;<a href="' . CalRoot . '/index.php?com=filter" title="' . $hc_lang_event['Filter'] . '">';
	echo (isset($_SESSION[$hc_cfg00 . 'hc_favCat']) || isset($_SESSION[$hc_cfg00 . 'hc_favCity'])) ? 
		'<img src="' . CalRoot . '/images/nav/filterR.png" alt="' . $hc_lang_event['Filter'] . '" /></a>':
		'<img src="' . CalRoot . '/images/nav/filter.png" alt="' . $hc_lang_event['Filter'] . '" /></a>';
	echo ' <a href="' . CalRoot . '/' . $mnuLoc . '" title="' . $hc_lang_event['Home'] . '"><img src="' . CalRoot . '/images/nav/home.png" alt="' . $hc_lang_event['Home'] . '" /></a>';
	echo ' <a href="' . $prevLink . $locSaver . '" title="' . $hc_lang_event['BrowseBack'] . ' ' . $lType . '"><img src="' . CalRoot . '/images/nav/left.png" alt="' . $hc_lang_event['BrowseBack'] . ' ' . $lType . '" /></a>';
	echo ' <a href="' . $nextLink . $locSaver . '" title="' . $hc_lang_event['BrowseForward'] . ' ' . $lType . '"><img src="' . CalRoot . '/images/nav/right.png" alt="' . $hc_lang_event['BrowseForward'] . ' ' . $lType . '" /></a>';
	echo '</div>';
	
	if(hasRows($result)){
		$cnt = 0;
		$curDate = "";
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
			echo '<a href="' . CalRoot . '/index.php?com=detail&amp;eID=' . $row[0] . $locSaver . '" class="eventListTitle">' . cOut($row[1]) . '</a></div>';
 			
			++$cnt;
		}//end while
	} else {
		echo $hc_lang_event['NoEventBrowse'];
		echo '<ol>';
		echo '<li style="line-height: 30px;">' . $hc_lang_event['NoEvent1'] . '<span class="miniCalEvents" style="padding:3px;">03</span></li>';
		echo '<li style="line-height: 30px;">' . $hc_lang_event['NoEvent2'] . '<span class="miniCalNav" style="padding:3px;">&lt;</span> <span class="miniCalNav" style="padding:3px;">&gt;</span></li>';
	
		echo (isset($_SESSION[$hc_cfg00 . 'hc_favCat']) || isset($_SESSION[$hc_cfg00 . 'hc_favCity'])) ? '<li>' . $hc_lang_event['NoEvent4'] . ' <a href="' . CalRoot . '/index.php?com=filter" class="eventMain"><img src="' . CalRoot . '/images/nav/filterR.png" width="16" height=\"16\" alt="' . $hc_lang_event['Filter'] . '" style="vertical-align:bottom;padding-top:10px;" /></a></li>' :'';
		echo "</ol>";
	}//end if	
	
	echo '<div id="nav-bottom">';
	echo '<a href="' . CalRoot . '/index.php?b=0' . $locSaver . '" title="' . $hc_lang_event['ALTBrowseW'] . '"><img src="' . CalRoot . '/images/nav/weekly.png" alt="' . $hc_lang_event['ALTBrowseW'] . '" /></a>';
	echo ' <a href="' . CalRoot . '/index.php?b=1' . $locSaver . '" title="' . $hc_lang_event['ALTBrowseM'] . '"><img src="' . CalRoot . '/images/nav/monthly.png" alt="' . $hc_lang_event['ALTBrowseM'] . '" /></a>';
	echo '&nbsp;&nbsp;<a href="' . CalRoot . '/index.php?com=filter" title="' . $hc_lang_event['Filter'] . '">';
	echo (isset($_SESSION[$hc_cfg00 . 'hc_favCat']) || isset($_SESSION[$hc_cfg00 . 'hc_favCity'])) ?
		'<img src="' . CalRoot . '/images/nav/filterR.png" alt="' . $hc_lang_event['Filter'] . '" /></a>':
		'<img src="' . CalRoot . '/images/nav/filter.png" alt="' . $hc_lang_event['Filter'] . '" /></a>';
	echo ' <a href="' . CalRoot . '/' . $mnuLoc . '" title="' . $hc_lang_event['Home'] . '"><img src="' . CalRoot . '/images/nav/home.png" alt="' . $hc_lang_event['Home'] . '" /></a>';
	echo ' <a href="' . $prevLink . $locSaver . '" title="' . $hc_lang_event['BrowseBack'] . ' ' . $lType . '"><img src="' . CalRoot . '/images/nav/left.png" alt="' . $hc_lang_event['BrowseBack'] . ' ' . $lType . '" /></a>';
	echo ' <a href="' . $nextLink . $locSaver . '" title="' . $hc_lang_event['BrowseForward'] . ' ' . $lType . '"><img src="' . CalRoot . '/images/nav/right.png" alt="' . $hc_lang_event['BrowseForward'] . ' ' . $lType . '" /></a>';
	echo '</div>';?>