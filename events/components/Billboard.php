<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

/*
	$hc_fillMax = 0;			//	Fill List With Upcoming Events
	$hc_maxShow = 10;			//	Number of Event to Display
	$hc_dateFormat = "m/d/y";	//	Date Output Format
	$hc_timeFormat = "h:i A";	//	Time Output Format
	$hc_showTime = 0;			//	Show Event Time 1 = Yes, 0 = No
	$hc_series = 0;				//	Show All Events in Series 1 = Yes, 0 = No
	$hc_timezoneOffset = 0;		//	Number of Hours to Modify Server Time By
*/
	$hourOffset = date("G");
	if($hc_timezoneOffset > 0){
		$hourOffset = $hourOffset + abs($hc_timezoneOffset);
	} else {
		$hourOffset = $hourOffset - abs($hc_timezoneOffset);
	}//end if
	
	$query = "	SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD
				FROM " . HC_TblPrefix . "events
				WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "'";
	if($hc_fillMax == 0){
		$query .= " AND IsBillboard = 1 ";
	}//end if
	
	$query .= " ORDER BY IsBillboard DESC, StartDate, Title LIMIT " . $hc_maxShow * 3;
	
	$result = doQuery($query);
	
	if(hasRows($result)){
		$curDate = "";
		$cnt = 0;
		$hideSeries[] = array();
		$showHeader = 0;
		echo "<ul class=\"billboard\">";
		while($row = mysql_fetch_row($result)){
			if($cnt >= $hc_maxShow){break;}//end if
			
			if($row[4] == 0 && $showHeader == 0){
				$showHeader = 1;
				echo "<li class=\"billboardDate\"><br /><b>Upcoming Events</b></li>";
			}//end if
			
			if($row[5] == '' || !in_array($row[5], $hideSeries)){
				if($curDate != $row[2]){
					$curDate = cOut($row[2]);
					$dateParts = explode("-", $row[2]);
					echo "<li class=\"billboardDate\"><br />" . stampToDate($row[2], $hc_dateFormat) . "</li>";
				}//end if
				
				if($hc_showTime == 1){
					$startTime = "";
					if($row[6] == 0){
						$timeparts = explode(":", $row[3]);
						$startTime = date($hc_timeFormat, mktime($timeparts[0], $timeparts[1], $timeparts[2], 1, 1, 1971));
					} elseif($row[6] == 1){
						$startTime = "All Day";
					} else {
						$startTime = "TBA";
					}//end if
					
					echo "<li class=\"billboard\"><a href=\"" . CalRoot ."/index.php?com=detail&amp;eID=" . $row[0] . "&amp;month=" . $dateParts[1] . "&amp;year=" . $dateParts[0] . "\" class=\"billboard\">" . cOut($row[1]) . "</a> - " . $startTime . "</li>";
				} else {
					echo "<li class=\"billboard\"><a href=\"" . CalRoot ."/index.php?com=detail&amp;eID=" . $row[0] . "&amp;month=" . $dateParts[1] . "&amp;year=" . $dateParts[0] . "\" class=\"billboard\">" . cOut($row[1]) . "</a></li>";
				}//end if
				
				$cnt++;
			}//end if
			
			if($hc_series == 0 && $row[5] != '' && (!in_array($row[5], $hideSeries))){
				$hideSeries[] = $row[5];
			}//end if
		}//end while
		echo "</ul>";
	} else {
		echo "<br />There are no billboard events currently available.";
	}//end if
?>