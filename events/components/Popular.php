<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= now() ORDER BY Views DESC LIMIT " . $hc_maxPopular);
	
	if(hasRows($result)){
		$curDate = "";
		$cnt = 0;
		echo "<ul class=\"popular\">";
		
		while($row = mysql_fetch_row($result)){
			
			if($curDate != $row[9]){
				$curDate = $row[9];
				$dateParts = explode("-", $row[9]);
				echo "<li class=\"popularDate\"><br />" . stampToDate($row[9], $hc_dateFormat) . "</li>";
			}//end if
			
			if($hc_showTime == 1){
				if($row[10] != ''){
					$timepart = explode(":", $row[10]);
					$startTime = date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 1, 1, 1971));
				} else {
					$startTime = "All Day";
				}//end if
				
				echo "<li class=\"popular\"><a href=\"" . CalRoot ."/index.php?com=detail&amp;eID=" . $row[0] . "&amp;month=" . $dateParts[1] . "&amp;year=" . $dateParts[0] . "\" class=\"popular\">" . cOut($row[1]) . "</a> - " . $startTime . "</li>";
				
			} else {
				echo "<li class=\"popular\"><a href=\"" . CalRoot ."/index.php?com=detail&amp;eID=" . $row[0] . "&amp;month=" . $dateParts[1] . "&amp;year=" . $dateParts[0] . "\" class=\"popular\">" . cOut($row[1]) . "</a></li>";
				
			}//end if
			$cnt = $cnt + 1;
		}//end while
		echo "</ul>";
	} else {
		echo "<br />There are no most popular events currently available.";
	}//end if
?>