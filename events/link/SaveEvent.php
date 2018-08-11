<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		
		doQuery("UPDATE " . HC_TblPrefix . "events SET Downloads = Downloads + 1 WHERE PkID = " . $_GET['eID']);
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . $_GET['eID']);
			//$dtStamp = "20040520T000832-0500";
		$dtStamp = date("Ymd\TH:i:sO", mktime(0, 0, 0, 1, 1, 1971));
		$uID = "Event Calendar Module " . mysql_result($result,0,0);
		
		$starttimepart = split(":", mysql_result($result,0,10));
		$startdatepart = split("-", mysql_result($result,0,9));
		
		$endtimepart = split(":", mysql_result($result,0,12));
		
		//check for start time
		if(mysql_result($result,0,10) != ''){
			$startDate = date("Ymd\TH:i:sO", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
			
			if(mysql_result($result,0,12) != ''){
				$endDate = date("Ymd\TH:i:sO", mktime($endtimepart[0], $endtimepart[1], $endtimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
			} else {
				$endDate = date("Ymd\TH:i:sO", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
			}//end if
		} else {
			$startDate = date("Ymd\TH:i:sO", mktime(00, 00, 00, $startdatepart[1], $startdatepart[2], $startdatepart[0]));
			$endDate = date("Ymd\TH:i:sO", mktime(00, 00, 00, $startdatepart[1], $startdatepart[2], $startdatepart[0]));
		}//end if
		
		$summary = mysql_result($result,0,1);
		$description = ereg_replace( "\n", "", mysql_result($result,0,8));
		
		if(mysql_result($result,0,35) == 0 OR mysql_result($result,0,35) == ''){
			$location = mysql_result($result,0,2);
		} else {
			$result = doQuery("SELECT Name FROM " . HC_TblPrefix . "locations WHERE PkID = " . mysql_result($result,0,35));
			$location = mysql_result($result,0,0);
		}//end if
		
		$descFooter = "\\n\\n_______________________________________________________________\\nThis Event Downloaded From a Helios " . HC_Version . " Powered Site";
		$descFooter .= "\\nView Full Event Details Here: " . CalRoot . "/index.php?com=detail&eID=" . $_GET['eID'];
		
		header('Content-type: text/x-vCalendar');
		header('Content-Disposition: inline; filename="' . $summary . '.vcs"');
		
		echo "BEGIN:VCALENDAR" . "\n";
		echo "VERSION:2.0" . "\n";
		echo "METHOD:PUBLISH" . "\n";
		echo "BEGIN:VEVENT" . "\n";
		echo "DTSTART:" . $startDate . "\n";
		echo "DTEND:" . $endDate . "\n";
		echo "LOCATION:" . $location . "\n";
		echo "TRANSP:TRANSPARENT" . "\n";
		echo "SEQUENCE:0" . "\n";
		echo "UID:" . $uID . "\n";
		echo "DTSTAMP:" . $dtStamp . "\n";
		echo "DESCRIPTION:" . cleanSpecialChars(strip_tags($description)) . $descFooter . "\n";
		echo "SUMMARY:" . cleanSpecialChars(strip_tags($summary)) . "\n";
		echo "PRIORITY:5" . "\n";
		echo "CLASS:PUBLIC" . "\n";
		echo "END:VEVENT" . "\n";
		echo "END:VCALENDAR" . "\n";
	} else {
		header('Location: ' . CalRoot);
	}//end if
?>
