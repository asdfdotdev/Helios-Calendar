<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/

/*	
	$hc_maxPopular = 10;			//	Number of Events to Display
	$hc_dateFormat = "m/d/y";		//	Date Output Format
	$hc_showTime = 0;				//	Show Event Time 1 = Yes, 0 = No
	$hc_timezoneOffset = 0;			//	Number of Hours to Modify Server Time By
	$hc_langPath = "../includes/lang/";		//	Path to /events/includes/lang Relative to this File
	$hc_langType = "english";				//	Language Pack to Use
	
	include($hc_langPath . $hc_langType . '/public/event.php');		//	Remove Language Pack Include Below This Line
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/public/event.php');

	$hourOffset = date("G");
	if($hc_timezoneOffset > 0){
		$hourOffset = $hourOffset + abs($hc_timezoneOffset);
	} else {
		$hourOffset = $hourOffset - abs($hc_timezoneOffset);
	}//end if
	
	$result = doQuery("	SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD
						FROM " . HC_TblPrefix . "events
						WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "' ORDER BY Views DESC LIMIT " . cIn($hc_maxPopular));
	if(hasRows($result)){
		$curDate = "";
		$cnt = 0;
		echo "<ul class=\"popular\">";
		
		while($row = mysql_fetch_row($result)){
			
			if($curDate != $row[2]){
				$curDate = $row[2];
				$dateParts = explode("-", $row[2]);
				echo "<li class=\"popularDate\"><br />" . stampToDate($row[2], $hc_dateFormat) . "</li>";
			}//end if
			
			if($hc_showTime == 1){
				if($row[6] == 0){
					$timeparts = explode(":", $row[3]);
					$startTime = strftime($hc_timeFormat, mktime($timeparts[0], $timeparts[1], $timeparts[2], 1, 1, 1971));
				} elseif($row[6] == 1) {
					$startTime = "<i>" . $hc_lang_event['AllDay'] . "</i>";
				} elseif($row[6] == 2) {
					$startTime = "<i>" . $hc_lang_event['TBA'] . "</i>";
				}//end if
				
				echo "<li class=\"popular\"><a href=\"" . CalRoot ."/index.php?com=detail&amp;eID=" . $row[0] . "&amp;month=" . $dateParts[1] . "&amp;year=" . $dateParts[0] . "\" rel=\"nofollow\" class=\"popular\">" . cOut($row[1]) . "</a> - " . $startTime . "</li>";
				
			} else {
				echo "<li class=\"popular\"><a href=\"" . CalRoot ."/index.php?com=detail&amp;eID=" . $row[0] . "&amp;month=" . $dateParts[1] . "&amp;year=" . $dateParts[0] . "\" rel=\"nofollow\" class=\"popular\">" . cOut($row[1]) . "</a></li>";
				
			}//end if
			$cnt = $cnt + 1;
		}//end while
		echo "</ul>";
	} else {
		echo "<br />" . $hc_lang_event['NoPopular'];
	}//end if
?>