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
	$isAction = 1;
	include('../includes/include.php');
	
	$lID = 0;
	if(isset($_GET['lID']) && is_numeric($_GET['lID'])){
		$lID = $_GET['lID'];
	}//end if
		
	if($lID > 0){
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($lID) . "'");
		$locName = mysql_result($result,0,1);
		$location = mysql_result($result,0,1) . " - " . mysql_result($result,0,2) . " " . mysql_result($result,0,3) . ", " . mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7);
		
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 35");
		if(mysql_result($result,0,0) == 0){
			$tzOffset = date("O");
		} else {	
			$tzOffset = date("O") + (mysql_result($result,0,0) * 100);
			
			if($tzOffset < 0){
				if(strlen($tzOffset) < 5){
					$tzOffset = ltrim($tzOffset,"-");
					$tzOffset = "-0" . $tzOffset;
				}//end if
			} elseif($tzOffset > 0) {
				if(strlen($tzOffset) < 4){
					$tzOffset = "+0" . $tzOffset;
				} else {
					$tzOffset = "+" . $tzOffset;
				}//end if
			} else {
				$tzOffset = "+0000";
			}//end if
		}//end if
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE LocID = '" . cIn($_GET['lID']) . "' AND StartDate >= '" . date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y"))) . "' ORDER BY StartDate, StartTime");
		
		$descFooter = "\\n______________________________\\nCalendar Subscription Powered by Helios Calendar";
		
		header('Content-type: text/Calendar');
		header('Content-Disposition: inline; filename="' . cleanXMLChars(str_replace(" ", "", $locName),1) . '.ics"');
		
		echo "BEGIN:VCALENDAR\n";
		echo "METHOD:PUBLISH\n";
		echo "CALSCALE:GREGORIAN\n";
		echo "PRODID:-//Refresh Web Development//Helios Calendar//EN\n";
		echo "VERSION:2.0\n";
		echo "X-FROM-URL:" . CalRoot . "/\n";
		echo "X-WR-RELCALID:" . cleanSpecialChars(strip_tags($locName)) . " Event : Powered by Helios Calendar\n";
		echo "X-WR-CALNAME:" . cleanSpecialChars($locName) . "\n";
		
		while($row = mysql_fetch_row($result)){
		
			$dtStamp = date("Ymd\TH:i:sO", mktime(0, 0, 0, 1, 1, 1971));
			$starttimepart = split(":", $row[10]);
			$startdatepart = split("-", $row[9]);
			$endtimepart = split(":", $row[12]);
			
			$allDay = false;
			if($row[10] != ''){
				$startDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
				
				if($row[12] != ''){
					if($row[10] > $row[12]){
						$endDate = date("Ymd\THis", mktime($endtimepart[0], $endtimepart[1], $endtimepart[2], $startdatepart[1], $startdatepart[2]+1, $startdatepart[0]));
					} else {
						$endDate = date("Ymd\THis", mktime($endtimepart[0], $endtimepart[1], $endtimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					}//end if
				} else {
					$endDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
				}//end if
			} else {
				$allDay = true;
				$startDate = date("Ymd", mktime(0, 0, 0, $startdatepart[1], $startdatepart[2], $startdatepart[0]));
				$endDate = date("Ymd", mktime(0, 0, 0, $startdatepart[1], $startdatepart[2] + 1, $startdatepart[0]));
			}//end if
			
			$summary = $row[1];
			$description = ereg_replace( "\n", "", $row[8]);
			
			echo "BEGIN:VEVENT\n";
			echo "URL;VALUE=URI:" . CalRoot . "/index.php?com=detail&eID=" . $row[0] . "\n";
			if($allDay == false){
				echo "DTSTART:" . $startDate . "\n";
				echo "DTEND:" . $endDate . "\n";
			} else {
				echo "DTSTART;VALUE=DATE:" . $startDate . "\n";
				echo "DTEND;VALUE=DATE:" . $endDate . "\n";
			}//end if
			echo "SUMMARY:" . cleanSpecialChars(strip_tags($summary)) . "\n";
			echo "DESCRIPTION:" . cleanSpecialChars(strip_tags(cleanBreaks($description, 1))) . $descFooter . "\n";
			echo "LOCATION:" . $location . "\n";
			echo "CATEGORIES:" . CalName . " Events\n";
			echo "PRIORITY:0\n";
			echo "TRANSP:TRANSPARENT\n";
			echo "END:VEVENT\n";
		}//end while
		
		echo "END:VCALENDAR";
	} else {
		header('Location: ' . CalRoot);
	}//end if	?>