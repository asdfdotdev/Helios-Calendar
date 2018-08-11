<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$query = "	SELECT PkID, Title, StartDate, StartTime, IsBillboard
				FROM " . HC_TblPrefix . "events
				WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= NOW()";
	if($hc_fillMax == 0){
		$query .= " AND IsBillboard = 1 ";
	}//end if
	
	$query .= " ORDER BY IsBillboard DESC, StartDate, Title LIMIT " . $hc_maxShow;
	
	$result = doQuery($query);
	
	if(hasRows($result)){
		$curDate = "";
		$cnt = 0;
		$showHeader = 0;
		echo "<ul class=\"billboard\">";
		while($row = mysql_fetch_row($result)){
			
			if($row[4] == 0 && $showHeader == 0){
				$showHeader = 1;
				echo "<li class=\"billboardDate\"><br /><b>Upcoming Events</b></li>";
			}//end if
			
			if($curDate != $row[2]){
				$curDate = cOut($row[2]);
				$dateParts = explode("-", $row[2]);
				echo "<li class=\"billboardDate\"><br />" . stampToDate($row[2], $hc_dateFormat) . "</li>";
			}//end if
			
			if($hc_showTime == 1){
				if($row[3] != ''){
					$timeparts = explode(":", $row[3]);
					$startTime = date($hc_timeFormat, mktime($timeparts[0], $timeparts[1], $timeparts[2], 1, 1, 1971));
				} else {
					$startTime = "All Day";
				}//end if
				
				echo "<li class=\"billboard\"><a href=\"" . CalRoot ."/index.php?com=detail&amp;eID=" . $row[0] . "&amp;month=" . $dateParts[1] . "&amp;year=" . $dateParts[0] . "\" class=\"billboard\">" . cOut($row[1]) . "</a> - " . $startTime . "</li>";
				
			} else {
				echo "<li class=\"billboard\"><a href=\"" . CalRoot ."/index.php?com=detail&amp;eID=" . $row[0] . "&amp;month=" . $dateParts[1] . "&amp;year=" . $dateParts[0] . "\" class=\"billboard\">" . cOut($row[1]) . "</a></li>";
				
			}//end if
			$cnt = $cnt + 1;
		}//end while
		echo "</ul>";
	} else {
		echo "<br />There are no billboard events currently available.";
	}//end if
?>