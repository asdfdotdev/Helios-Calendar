<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
/*
	$incPrefix = 'events/';															//	Path from where you integrate this file to your /events/ directory
	include_once($incPrefix . 'includes/include_int.php');							//	Include for your int_include.php file (/events/includes/int_include.php)
	include_once($incPrefix . 'includes/lang/' . $hc_cfg28 . '/public/event.php');	//	New Include for your event.php language pack file
*/	
	echo '<form name="frmJump" id="frmJump" action="">';
	echo '<table cellpadding="0" cellspacing="0" border="0" class="miniCalTable" align="center">';
	
	$hourOffset = date("G") + ($hc_cfg35);
	$sysDateParts = explode("-",date("Y-m-d", mktime($hourOffset,0,0,date("m"),date("d"),date("Y"))));
	$sysYear = $sysDateParts[0];
	$sysMonth = $sysDateParts[1];
	$sysDay = $sysDateParts[2];
	$curYear = (isset($_GET['year']) && is_numeric($_GET['year'])) ? $_GET['year'] : $sysYear;
	$curMonth = (isset($_GET['month']) && is_numeric($_GET['month'])) ? $_GET['month'] : $sysMonth;
	
	if(!checkdate($curMonth, 1, $curYear)){
		$curYear = $sysYear;
		$curMonth = $sysMonth;
	} elseif(($curMonth < $sysMonth && $curYear == $sysYear) && $hc_cfg11 == 0){
		$curMonth = $sysMonth;
	} elseif(($curYear < $sysYear) && $hc_cfg11 == 0){
		$curMonth = $sysMonth;
		$curYear = $sysYear;
	}//end if
	
	$stopDay = date("t", mktime(0,0,0,$curMonth,1,$curYear));
	
	$query = "	SELECT DISTINCT e.StartDate
				FROM " . HC_TblPrefix . "events e
					LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
					LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID)
				WHERE e.IsActive = 1 AND 
					  e.IsApproved = 1 AND
					  (e.StartDate BETWEEN '" . date("Y-m-d", mktime(0,0,0,$curMonth,1,$curYear)) . "' AND '" . date("Y-m-d", mktime(0,0,0,$curMonth+1,0,$curYear)) . "')";
	
	if(isset($_SESSION[$hc_cfg00 . 'hc_favCat']) && $_SESSION[$hc_cfg00 . 'hc_favCat'] != ''){
		$query .= " AND ec.CategoryID in (" . $_SESSION[$hc_cfg00 . 'hc_favCat'] . ") ";
	}//end if
	
	if(isset($_SESSION[$hc_cfg00 . 'hc_favCity']) && $_SESSION[$hc_cfg00 . 'hc_favCity'] != ''){
		$query .= " AND (e.LocationCity IN (" . $_SESSION[$hc_cfg00 . 'hc_favCity'] . ") OR l.City IN (" . $_SESSION[$hc_cfg00 . 'hc_favCity'] . "))";
	}//end if
	
	$locSaver = "";
	if(isset($_GET['lID']) && is_numeric($_GET['lID'])){
		$query .= " AND LocID = '" . cIn($_GET['lID']) . "'";
		$locSaver = "&lID=" . cIn($locID);
	}//end if
	
	$query .= " ORDER BY StartDate";
	$result = doQuery($query);
	
	$events = array();
	if(hasRows($result)){
		while($row = mysql_fetch_row($result)){
			$events[] = stampToDate($row[0], "%d");
		}//end while
	}//end if
	
	$navBackMonth = ($curMonth-1) % 12;
	$navBackYear = $curYear;
	$navForwMonth = ($curMonth+1) % 12;
	$navForwYear = $curYear;
	if($curMonth == 1){
		$navBackMonth = 12;
		$navBackYear = $curYear - 1;
	} elseif($curMonth == 11) {
		$navForwMonth = 12;
	} elseif($curMonth == 12) {
		$navForwYear = $curYear + 1;
	}//end if
	
	echo '<tr><td class="miniCalNav" onclick="window.location.href=\'' . CalRoot . '/index.php?year=' . $navBackYear . '&amp;month=' . $navBackMonth . $locSaver . '\'">&lt;</td>';
	echo '<td class="miniCalTitle" colspan="5">';
	echo '<select name="jumpMonth" id="jumpMonth" class="miniCalJump" onchange="window.location.href=document.frmJump.jumpMonth.value;">';
	$jumpMonth = date("n", mktime(0,0,0,$curMonth-12,1,$curYear));
	$jumpYear = date("Y", mktime(0,0,0,$curMonth-12,1,$curYear));
	
	$i = 0;
	while($i <= 24){
		echo ($i == 12) ? "<option selected=\"selected\" ":"<option ";
		echo 'value="' . CalRoot . '/index.php?year=' . date("Y", mktime(0,0,0,$jumpMonth + $i,1,$jumpYear)) . '&amp;month=' . date("n", mktime(0,0,0,$jumpMonth + $i,1,$jumpYear)) . $locSaver . '">' . strftime("%b %Y", mktime(0,0,0,$jumpMonth + $i,1,$jumpYear)) . '</option>';
		++$i;
	}//end while
	echo '</select></td>';
	echo '<td class="miniCalNav" onclick="window.location.href=\'' . CalRoot . '/index.php?year=' . $navForwYear . '&amp;month=' . $navForwMonth . $locSaver . '\';">&gt;</td>';
	echo '</tr><tr>';
	
	$i = $hc_cfg22;
	$daysOfWeek = $hc_lang_config['MiniCalDays'];
	while($i < $hc_cfg22 + 7){
		echo '<td class="miniCalDOW">' . $daysOfWeek[$i % 7] . '</td>';
		++$i;
	}//end for
	echo "</tr><tr>";
	
	$i = 0;
	$fillCnt = (((date("w", mktime(0,0,0,$curMonth,1,$curYear)) - $hc_cfg22) + 7) % 7);
	while($i < $fillCnt){
		echo '<td class="miniCalFiller">&nbsp;</td>';
		++$i; 	
	}//end while
	
	$x = 1;
	while($x <= $stopDay){
		if(($x + $i) % 7 == 1){echo "</tr><tr>";}
		echo '<td ';
		if(($x == $sysDay) && ($sysMonth == $curMonth) && ($sysYear == $curYear)){
			echo 'class="miniCalToday"';
		} elseif(in_array($x, $events)) {
			echo 'class="miniCalEvents"';
		} else {
			echo 'class="miniCal"';
		}//end if
		echo ' onclick="window.location.href=\'' . CalRoot . '/index.php?year=' . $curYear . '&amp;month=' . $curMonth . '&amp;day=' . $x . $locSaver . '\';">' . date("d", mktime(0,0,0,$curMonth,$x,$curYear)) . '</td>';
		++$x;
	}//end while
	
	$x = ($x + $i - 1) % 7;
	while($x < 7 && $x != 0){
		echo '<td class="miniCalFiller">&nbsp;</td>';
		++$x;
	}//end while
	echo '</tr></table></form>';?>