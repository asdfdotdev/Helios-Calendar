<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$isAction = 1;
	include('../includes/include.php');
	
	$eID = 0;
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
	}//end if
	
	$cID = 0;
	if(isset($_GET['cID']) && is_numeric($_GET['cID'])){
		$cID = $_GET['cID'];
	}//end if
	
	if($eID > 0 && $cID > 0){
		switch($cID){
			case 1:
				doQuery("UPDATE " . HC_TblPrefix . "events SET Downloads = Downloads + 1 WHERE PkID = " . $_GET['eID']);
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . $_GET['eID']);
				$link = "http://www.google.com/calendar/event?action=TEMPLATE";
				if(mysql_result($result,0,11) == 0){
					if(mysql_result($result,0,12) != ''){
						$link .= "&dates=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "Ymd\THis") . "/" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,12), "Ymd\THis");
					} else {
						$link .= "&dates=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "Ymd\THis") . "/" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "Ymd\THis");
					}//end if
				} else {
					$link .= "&dates=" . stampToDate(mysql_result($result,0,9), "Ymd") . "/" . stampToDate(mysql_result($result,0,9), "Ymd");
				}//end if
				
				$link .= "&text=" . urlencode(mysql_result($result,0,1));
				if(strlen(mysql_result($result,0,8)) > 1000){
					$link .= "&details=" . urlencode(substr(mysql_result($result,0,8),0,1000) . "...<br /><br />Full Description available at: " . CalRoot . "/index.php?com=details&eID=" . $eID);
				} else {
					$link .= "&details=" . urlencode(mysql_result($result,0,8));
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
					$link .= "&st=" . stampToDate(mysql_result($result,0,9), "Ymd");
				} else {
					$link .= "&st=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "Ymd\THis");
				}//end if
				$link .= "&title=" . urlencode(mysql_result($result,0,1));
				
				if(strlen(mysql_result($result,0,8)) > 1000){
					$link .= "&desc=" . urlencode(strip_tags(substr(mysql_result($result,0,8),0,1000) . "...\n\nFull Description available at: " . CalRoot . "/index.php?com=details&eID=" . $eID));
				} else {
					$link .= "&desc=" . urlencode(strip_tags(mysql_result($result,0,8)));
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
				
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($_GET['eID']));
				
				$dtStamp = date("Ymd\TH:i:sO", mktime(0, 0, 0, 1, 1, 1971));
				$starttimepart = split(":", mysql_result($result,0,10));
				$startdatepart = split("-", mysql_result($result,0,9));
				$endtimepart = split(":", mysql_result($result,0,12));
				
				//check for start time
				if(mysql_result($result,0,10) != ''){
					
					$startDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					
					if(mysql_result($result,0,12) != ''){
						$endDate = date("Ymd\THis", mktime($endtimepart[0], $endtimepart[1], $endtimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					} else {
						$endDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					}//end if
				} else {
					$startDate = date("Ymd\THis", mktime(0, 0, 0, $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					$endDate = date("Ymd\THis", mktime(0, 0, 0, $startdatepart[1], $startdatepart[2], $startdatepart[0]));
				}//end if
				
				$summary = mysql_result($result,0,1);
				$description = ereg_replace( "\n", "", mysql_result($result,0,8));
				
				if(mysql_result($result,0,35) == 0 OR mysql_result($result,0,35) == ''){
					$location = mysql_result($result,0,2) . " - " . mysql_result($result,0,3) . " " . mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . ", " . mysql_result($result,0,6) . " " . mysql_result($result,0,37) . " " . mysql_result($result,0,7);
				} else {
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . mysql_result($result,0,35));
					$location = mysql_result($result,0,1) . " - " . mysql_result($result,0,2) . " " . mysql_result($result,0,3) . ", " . mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7);
				}//end if
				
				$result = doQuery("	SELECT c.CategoryName
									FROM " . HC_TblPrefix . "eventcategories ec
										LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
									WHERE ec.EventID = 1");
				if(hasRows($result)){
					$categories = "";
					while($row = mysql_fetch_row($result)){
						$categories .= $row[0] . ",";
					}//end while
				}//end if
				
				$descFooter = "\\n\\n______________________________\\nThis Event Downloaded From a Helios Calendar Powered Site";
				
				if($cID == 3){
					header('Content-type: text/Calendar');
					header('Content-Disposition: inline; filename="' . str_replace(" ", "", $summary) . '.ics"');
				} else {
					header('Content-type: text/x-vCalendar');
					header('Content-Disposition: inline; filename="' . str_replace(" ", "", $summary) . '.vcs"');
				}//end if
				
				echo "BEGIN:VCALENDAR\n";
				echo "METHOD:PUBLISH\n";
				echo "CALSCALE:GREGORIAN\n";
				echo "PRODID:-//Refresh Web Development//Helios Calendar//EN\n";
				echo "VERSION:2.0\n";
				echo "X-FROM-URL:" . CalRoot . "/index.php?com=details&eID=" . $eID . "\n";
				echo "X-WR-RELCALID:" . CalName . " " . cleanSpecialChars(strip_tags($summary)) . " " . $startDate . "\n";
				
				if($cID == 3){
					echo "X-WR-CALNAME:" . cleanSpecialChars(strip_tags($summary)) . "\n";
					echo "BEGIN:VEVENT\n";
					echo "URL;VALUE=URI:" . CalRoot . "/index.php?com=detail&eID=" . $eID . "\n";
					echo "DTSTART:" . $startDate . "\n";
					echo "DTEND:" . $endDate . "\n";
				} else {
					echo "BEGIN:VEVENT\n";
					echo "URL;VALUE=URI:" . CalRoot . "/index.php?com=detail&eID=" . $eID . "\n";
					echo "DTSTART:" . $startDate . $tzOffset . "Z\n";
					echo "DTEND:" . $endDate . $tzOffset . "Z\n";
				}//end if
				
				echo "SUMMARY:" . cleanSpecialChars(strip_tags($summary)) . "\n";
				echo "DESCRIPTION:" . wordwrap(cleanSpecialChars(strip_tags($description)) . $descFooter . "\n", 80,"\n\t", 1);
				echo "LOCATION:" . $location . "\n";
				echo "CATEGORIES:" . CalName . " Events\n";
				echo "PRIORITY:0\n";
				echo "TRANSP:TRANSPARENT\n";
				
				echo "END:VEVENT\n";
				echo "END:VCALENDAR";
				
				break;
		}//end switch
	} else {
		header('Location: ' . CalRoot);
	}//end if
?>
