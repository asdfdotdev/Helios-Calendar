<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');
	include('tzs.php');
	
	$tzSelect = date("O") + (($hc_cfg35 - date("I")) * 100);
	$tzSend = $hc_timezones[$tzSelect];
	$hourOffset = date("G") + ($hc_cfg35);
	$tzOffset = date("O") + ($hc_cfg35 * 100);
	
	if($tzOffset == 0){
		$tzOffset = "+0000";
	} elseif($tzOffset < 0){
		if(strlen($tzOffset) < 5){
			$tzOffset = ltrim($tzOffset,"-");
			$tzOffset = "-0" . $tzOffset;
		}//end if
	} elseif($tzOffset > 0) {
		$tzOffset = (strlen($tzOffset) < 4) ? '+0' . $tzOffset : '+' . $tzOffset;
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "'");
	
	header('Content-type: text/Calendar');
	header('Content-Disposition: inline; filename="' . CalName . '.ics"');
	
	echo "BEGIN:VCALENDAR\r\n";
	echo "VERSION:2.0\r\n";
	echo "METHOD:PUBLISH\r\n";
	echo "CALSCALE:GREGORIAN\r\n";
	echo "PRODID:-//Refresh Web Development//Helios Calendar//EN\r\n";
	echo "X-FROM-URL:" . CalRoot . "/\r\n";
	echo "X-WR-RELCALID:" . CalName . "\r\n";
	echo "X-WR-CALNAME:" . CalName . "\r\n";
	echo "X-WR-TIMEZONE:" . $tzSend . "\r\n";
	
	while($row = mysql_fetch_row($result)){
		$dtStamp = date("Ymd\TH:i:sO", mktime(0, 0, 0, 1, 1, 1971));
		$starttimepart = explode(":", $row[10]);
		$startdatepart = explode("-", $row[9]);
		$endtimepart = explode(":", $row[12]);
		
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
		$description = $row[8];
		
		if($row[35] == 0 OR $row[35] == ''){
			$location = $row[2] . " - " . $row[3] . " " . $row[4] . ', ' . $row[5] . ', ' . $row[6] . " " . $row[37] . " " . $row[7];
		} else {
			$result2 = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . $row[35]);
			if(hasRows($result2)){
				$location = mysql_result($result2,0,1) . "test - " . mysql_result($result2,0,2) . " " . mysql_result($result2,0,3) . ", " . mysql_result($result2,0,4) . ", " . mysql_result($result2,0,5) . " " . mysql_result($result2,0,6) . " " . mysql_result($result2,0,7);
			} else {
				$location = "";
			}//end if
		}//end if
		
		$descFooter = "\\n______________________________\\nThis Event Downloaded From a Helios Calendar Powered Site";
		
		echo "BEGIN:VEVENT\r\n";
		echo "URL;VALUE=URI:" . CalRoot . "/index.php?com=detail&eID=" . $row[0] . "\r\n";
		if($allDay == false){
			echo "DTSTART:" . $startDate . "\r\n";
			echo "DTEND:" . $endDate . "\r\n";
		} else {
			echo "DTSTART;VALUE=DATE:" . $startDate . "\r\n";
			echo "DTEND;VALUE=DATE:" . $endDate . "\r\n";
		}//end if
		echo "SUMMARY:" . str_replace(";", "\;",str_replace(",", "\,",html_entity_decode(cOut(cleanSpecialChars(strip_tags($summary)))))) . "\r\n";
		echo "DESCRIPTION:" . str_replace(";", "\;",str_replace(",", "\,",html_entity_decode(cOut(cleanSpecialChars(strip_tags(cleanBreaks($description))))) . $descFooter)) . "\r\n";
		echo "LOCATION:" . str_replace(";", "\;",str_replace(",", "\,",html_entity_decode(cOut($location)))) . "\r\n";
		echo "CATEGORIES:" . CalName . " Events\r\n";
		echo "PRIORITY:0\r\n";
		echo "TRANSP:TRANSPARENT\r\n";
		echo "END:VEVENT\r\n";
	}//end while
	echo "END:VCALENDAR";?>