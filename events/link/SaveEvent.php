<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? $_GET['eID'] : 0;
	$cID = (isset($_GET['cID']) && is_numeric($_GET['cID'])) ? $_GET['cID'] : 0;
	
	if($eID > 0 && $cID > 0){
		switch($cID){
			case 1:
				doQuery("UPDATE " . HC_TblPrefix . "events SET Downloads = Downloads + 1 WHERE PkID = " . $_GET['eID']);
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . $_GET['eID']);
				$link = "http://www.google.com/calendar/event?action=TEMPLATE";
				if(mysql_result($result,0,11) == 0){
					if(mysql_result($result,0,12) != ''){
						$link .= "&dates=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S") . "/" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,12), "%Y%m%dT%H%M%S");
					} else {
						$link .= "&dates=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S") . "/" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S");
					}//end if
				} else {
					$link .= "&dates=" . stampToDate(mysql_result($result,0,9), "%Y%m%d") . "/" . stampToDate(mysql_result($result,0,9), "%Y%m%d");
				}//end if
				
				$link .= "&text=" . urlencode(mysql_result($result,0,1));
				if(strlen(mysql_result($result,0,8)) > 400){
					$link .= "&details=" . urlencode(htmlspecialchars(strip_tags(cOut(substr(mysql_result($result,0,8),0,400)))) . "...<br /><br />Full Description available at: " . CalRoot . "/index.php?com=details&eID=" . $eID);
				} else {
					$link .= "&details=" . urlencode(htmlspecialchars(strip_tags(cOut(mysql_result($result,0,8)))));
				}//end if
				if(mysql_result($result,0,35) == 0 || mysql_result($result,0,35) == ''){
					$link .= "&location=" . urlencode(mysql_result($result,0,3) . " " . mysql_result($result,0,4) . " " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,37) . " " . mysql_result($result,0,7));
				} else {
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . mysql_result($result,0,35));
					$link .= "&location=" . urlencode(mysql_result($result,0,2) . " " . mysql_result($result,0,3) . " " . mysql_result($result,0,4) . " " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7));
				}//end if
				
				header("Location: $link");
				break;
			case 2:
				doQuery("UPDATE " . HC_TblPrefix . "events SET Downloads = Downloads + 1 WHERE PkID = " . $_GET['eID']);
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . $_GET['eID']);
				$link = "http://calendar.yahoo.com/?v=60&view=d&type=20";
				if(mysql_result($result,0,11) == 1){
					$link .= "&st=" . stampToDate(mysql_result($result,0,9), "%Y%m%d");
				} else {
					$link .= "&st=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S");
				}//end if
				$link .= "&title=" . urlencode(mysql_result($result,0,1));
				
				if(strlen(mysql_result($result,0,8)) > 400){
					$link .= "&desc=" . urlencode(strip_tags(substr(mysql_result($result,0,8),0,400) . "...\n\nFull Description available at: " . CalRoot . "/index.php?com=details&eID=" . $eID));
				} else {
					$link .= "&description=" . urlencode(strip_tags(mysql_result($result,0,8)));
				}//end if
				
				if(mysql_result($result,0,35) == 0 || mysql_result($result,0,35) == ''){
					$link .= "&in_loc=" . urlencode(mysql_result($result,0,2));
					$link .= "&in_st=" . urlencode(mysql_result($result,0,3) . " " . mysql_result($result,0,4));
					$link .= "&in_csz=" . urlencode(mysql_result($result,0,5) . ", " . mysql_result($result,0,6) . " " . mysql_result($result,0,37) . " " . mysql_result($result,0,7));
				} else {
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . mysql_result($result,0,35));
					$link .= "&in_loc=" . urlencode(mysql_result($result,0,1));
					$link .= "&in_st=" . urlencode(mysql_result($result,0,2) . " " . mysql_result($result,0,3));
					$link .= "&in_csz=" . urlencode(mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7));
				}//end if
				header("Location: $link");
				break;
			case 3:
			case 4:
				doQuery("UPDATE " . HC_TblPrefix . "events SET Downloads = Downloads + 1 WHERE PkID = " . cIn($_GET['eID']));
				
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
				
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($_GET['eID']));
				$dtStamp = date("Ymd\TH:i:sO", mktime(0, 0, 0, 1, 1, 1971));
				$starttimepart = split(":", mysql_result($result,0,10));
				$startdatepart = split("-", mysql_result($result,0,9));
				$endtimepart = split(":", mysql_result($result,0,12));
				
				$allDay = false;
				if(mysql_result($result,0,10) != ''){
					$startDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					
					if(mysql_result($result,0,12) != ''){
						if(mysql_result($result,0,10) > mysql_result($result,0,12)){
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
				
				$summary = mysql_result($result,0,1);
				$description = ereg_replace( "\n", "", mysql_result($result,0,8));
				
				if(mysql_result($result,0,35) == 0 OR mysql_result($result,0,35) == ''){
					$location = mysql_result($result,0,2) . " - " . mysql_result($result,0,3) . " " . mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . ", " . mysql_result($result,0,6) . " " . mysql_result($result,0,37) . " " . mysql_result($result,0,7);
				} else {
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . mysql_result($result,0,35));
					$location = mysql_result($result,0,1) . " - " . mysql_result($result,0,2) . " " . mysql_result($result,0,3) . ", " . mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7);
				}//end if
				
				$descFooter = "\\n______________________________\\nThis Event Downloaded From a Helios Calendar Powered Site";
				
				if($cID == 3){
					header('Content-type: text/Calendar');
					header('Content-Disposition: inline; filename="' . cleanXMLChars(str_replace(" ", "", $summary),1) . '.ics"');
					$iVersion = "2.0";
				} else {
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header('Content-type: text/x-vCalendar');
					header('Content-Disposition: inline; filename="' . cleanXMLChars(str_replace(" ", "", $summary),1) . '.vcs"');
					$iVersion = "1.0";
				}//end if
				
				echo "BEGIN:VCALENDAR\n";
				echo "METHOD:PUBLISH\n";
				echo "CALSCALE:GREGORIAN\n";
				echo "PRODID:-//Refresh Web Development//Helios Calendar//EN\n";
				echo "VERSION:" . $iVersion . "\n";
				echo "X-FROM-URL:" . CalRoot . "/index.php?com=details&eID=" . $eID . "\n";
				echo "X-WR-RELCALID:" . CalName . " " . cleanSpecialChars(strip_tags($summary)) . " " . $startDate . "\n";
				
				if($cID == 3){
					echo "X-WR-CALNAME:" . cleanSpecialChars(strip_tags($summary)) . "\n";
					echo "BEGIN:VEVENT\n";
					echo "URL;VALUE=URI:" . CalRoot . "/index.php?com=detail&eID=" . $eID . "\n";
					if($allDay == false){
						echo "DTSTART:" . $startDate . "\n";
						echo "DTEND:" . $endDate . "\n";
					} else {
						echo "DTSTART;VALUE=DATE:" . $startDate . "\n";
						echo "DTEND;VALUE=DATE:" . $endDate . "\n";
					}//end if
				} else {
					echo "BEGIN:VEVENT\n";
					echo "URL;VALUE=URI:" . CalRoot . "/index.php?com=detail&eID=" . $eID . "\n";
					if($allDay == false){
						echo "DTSTART:" . $startDate . $tzOffset . "Z\n";
						echo "DTEND:" . $endDate . $tzOffset . "Z\n";
					} else {
						echo "DTSTART:" . $startDate . "T000000" . $tzOffset . "Z\n";
						echo "DTEND:" . $endDate . "T000000" . $tzOffset . "Z\n";
					}//end if
				}//end if
			
				echo "SUMMARY:" . cleanSpecialChars(strip_tags($summary)) . "\n";
				echo "DESCRIPTION:" . cleanSpecialChars(strip_tags(cleanBreaks($description, 1))) . $descFooter . "\n";
				echo "LOCATION:" . $location . "\n";
				echo "CATEGORIES:" . CalName . " Events\n";
				echo "PRIORITY:0\n";
				echo "TRANSP:TRANSPARENT\n";
				echo "END:VEVENT\n";
				echo "END:VCALENDAR";
				break;
			case 5:				
				doQuery("UPDATE " . HC_TblPrefix . "events SET Downloads = Downloads + 1 WHERE PkID = " . $_GET['eID']);
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . $_GET['eID']);
				$link = "http://calendar.live.com/calendar/calendar.aspx?rru=addevent";

				if(mysql_result($result,0,11) == 0){
					if(mysql_result($result,0,12) != ''){
						$link .= "&dtstart=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S") . "&dtend=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,12), "%Y%m%dT%H%M%S");
					} else {
						$link .= "&dtstart=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S") . "&dtend=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S");
					}//end if
				} else {
					$link .= "&dtstart=" . stampToDate(mysql_result($result,0,9), "%Y%m%d") . "T000000&dtend=" . stampToDate(mysql_result($result,0,9), "%Y%m%d") . "T000000";
				}//end if

				$link .= "&summary=" . urlencode(mysql_result($result,0,1));
			
				if(strlen(mysql_result($result,0,8)) > 400){
					$link .= "&description=" . urlencode(htmlspecialchars(strip_tags(cOut(substr(mysql_result($result,0,8),0,400)))) . "...\n\nFull Description available at: " . CalRoot . "/index.php?com=details&eID=" . $eID);
				} else {
					$link .= "&description=" . urlencode(htmlspecialchars(strip_tags(cOut(mysql_result($result,0,8)))));
				}//end if
			
				if(mysql_result($result,0,35) == 0 || mysql_result($result,0,35) == ''){
					$link .= "&location=" . urlencode(mysql_result($result,0,3) . " " . mysql_result($result,0,4) . " " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,37) . " " . mysql_result($result,0,7));
				} else {
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . mysql_result($result,0,35));
					$link .= "&location=" . urlencode(mysql_result($result,0,2) . " " . mysql_result($result,0,3) . " " . mysql_result($result,0,4) . " " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7));
				}//end if
				
				header("Location: $link");
				break;
		}//end switch
	} else {
		header('Location: ' . CalRoot);
	}//end if?>